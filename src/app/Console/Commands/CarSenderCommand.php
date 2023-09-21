<?php

namespace App\Console\Commands;

use App\Classes\Dto\Message;
use App\Classes\Storages\TelegramStorage;
use App\Enums\SendType;
use App\Enums\SourceType;
use App\Enums\Transport;
use App\Models\DirtyCarData;
use App\Models\Group;
use App\Services\MessageService;
use App\Services\ParametersService;
use App\Services\SenderService;
use App\Services\TelegramSettingsService;
use Illuminate\Console\Command;

class CarSenderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:car-sender-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish cars in groups';

    private SourceType $type;

    function __construct(
        private ParametersService $parametersService,
        private MessageService $messageService,
        private SenderService $senderService,
        private TelegramSettingsService $telegramSettingsService,
        private TelegramStorage $telegramStorage
    ) {
        parent::__construct();
        $this->type = SourceType::Car;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->senderService->isTimeToSend()) {
            $this->info('sleep');
            return;
        }

        $groups = Group::where('is_active', true)->where('type', $this->type)->get();
        $topicesNames = $groups->filter(function ($group) {
            if (!empty($group->topic_name)) {
                return $group;
            }
        })->map(function ($group) {
            return $group->topic_name;
        });

        // get all keys for dictionary name brand
        $properties = $this->parametersService->getBrendDirtyParametersKeys();

        // exclude aready senden models
        $all = DirtyCarData::with('dirtyCarParametersData')
            ->whereDate('dirty_car_data.created_at', '>', now()->subHour(1))
            ->leftJoin('complete_messages', function ($join) {
                $join->on('dirty_car_data.id', '=', 'complete_messages.model_id')
                    ->where('complete_messages.model', DirtyCarData::class);
            })
            ->whereNull('complete_messages.model_id')->get([
                'dirty_car_data.id',
                'dirty_car_data.url',
                'dirty_car_data.name',
                'dirty_car_data.brand',
                'dirty_car_data.price',
                'dirty_car_data.images'
            ]);

        // set brand key from relation to property
        $arrBrands = $all->filter(function ($item) use ($topicesNames, $properties) {
            $current = $item->dirtyCarParametersData->filter(function ($item) use ($properties) {
                return in_array($item->property, $properties);
            });
            $brand = $current->first()->value;
            if (in_array($brand,$topicesNames->toArray())) {
                $item->brand = $current->first()->value;
                return $item;
            }
        })->groupBy(function ($item) {
            return $item->brand;
        });

        /*dd($all->groupBy(function($item){
            return $item->brand;
        })->map(function($item){
            return $item->count();
        })->sortDesc());*/


        // dd($arrBrands);        
        $this->senderService->init(SendType::Auto, Transport::Telegram, $this->type);
        // send to telegram
        foreach ($groups as $topic) {
            if (isset($arrBrands[$topic['topic_name']])) {
                $model = $arrBrands[$topic['topic_name']]->pop();

                $message = $this->messageService->createMessageDto($model);
                $cleanParams = $this->parametersService->getCleanValues($model->dirtyCarParametersData->pluck('value', 'property'));
                $message = $this->messageService->updateMessageDto($message, $cleanParams);

                $dto = new Message(SendType::Auto, [$topic->group_id, $topic->topic], Transport::Telegram, $message);

                $this->senderService->sendMessage($dto);
            }
        }

        $this->info('Finish');
    }
}

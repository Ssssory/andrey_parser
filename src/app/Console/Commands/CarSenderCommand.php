<?php

namespace App\Console\Commands;

use App\Jobs\SendCarToTelegramJob;
use App\Models\DirtyCarData;
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

    function __construct(
        private ParametersService $parametersService,
        private MessageService $messageService,
        private SenderService $senderService,
        private TelegramSettingsService $telegramSettingsService
    ) {
        parent::__construct();
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

        // get all keys for dictionary name brand
        $properties = $this->parametersService->getBrendDirtyParametersKeys();

        // exclude aready senden models
        $all = DirtyCarData::with('dirtyCarParametersData')
            ->whereDate('dirty_car_data.created_at', '>', now()
                ->subHour(1))->leftJoin('complete_messages', function ($join) {
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
        foreach ($all as $model) {
            $current = $model->dirtyCarParametersData->filter(function ($item) use ($properties) {
                return in_array($item->property, $properties);
            });
            $model->brand = $current->first()->value;
        }

        /*dd($all->groupBy(function($item){
            return $item->brand;
        })->map(function($item){
            return $item->count();
        })->sortDesc());*/

        // map models to array with key brand
        $arrBrands = $all->groupBy(function ($item) {
            return $item->brand;
        });

        // send to telegram
        $groupForum = $this->telegramSettingsService->getCarForumGroup();

        foreach ($groupForum['topics'] as $topic) {
            if (isset($arrBrands[$topic['name']])) {
                $model = $arrBrands[$topic['name']]->pop();

                SendCarToTelegramJob::dispatch($model, $groupForum['id'], $topic['id']);

                unset($arrBrands[$topic['name']]);
                unset($message);
                sleep(31);
            }
        }
        $autoGroup = $this->telegramSettingsService->getCarGroup();
        $chatId = $autoGroup['id'];

        if (!$arrBrands->isEmpty()) {
            foreach ($arrBrands as $collection) {
                $model = $collection->pop();

                SendCarToTelegramJob::dispatch($model, $chatId);
                unset($message);
                sleep(31);
            }
        }

        $this->info('Finish');
    }
}

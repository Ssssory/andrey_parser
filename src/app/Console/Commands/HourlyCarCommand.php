<?php

namespace App\Console\Commands;

use App\Classes\Car\Polovniautomobili;
use App\Enums\Sources;
use App\Jobs\TempPolovniautomobiliJob;
use App\Models\DirtyCarData;
use App\Models\Url;
use App\Services\MessageService;
use App\Services\ParametersService;
use App\Services\SenderService;
use App\Services\TelegramSettingsService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class HourlyCarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hourly-car-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce cars and publish in group';

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
        $morning = CarbonImmutable::createFromTime(9, 03, 00, 'Europe/Belgrade');
        $evening = CarbonImmutable::createFromTime(20, 03, 00, 'Europe/Belgrade');
        $now = CarbonImmutable::now('Europe/Belgrade');
        if (!$now->between($morning, $evening)) {
            $this->info('sleep');
            return;
        }

        $polovniautomobili = new Polovniautomobili();

        $polovniautomobili->getUrlsFromFillter();

        $urls = Url::where('status', 'new')->where('source', Sources::Polovniautomobili->name)->get();
        if ($urls->isEmpty()) {
            $this->info('No new urls');
            return;
        }
        $this->info('Start parsing');

        foreach ($urls as $url) {
            $url->status = 'in progress';
            $url->save();
            TempPolovniautomobiliJob::dispatch($url);
            sleep(5);
        }

        $properties = $this->parametersService->getBrendDirtyParametersKeys();

        // $all = DirtyCarData::with('dirtyCarParametersData')->leftJoin('complete_messages', function ($join) {
        $all = DirtyCarData::with('dirtyCarParametersData')
            ->whereDate('created_at', '>', now()
            ->subDays(1))->leftJoin('complete_messages', function ($join) {
                $join->on('dirty_car_data.id', '=', 'complete_messages.model_id')
                    ->where('complete_messages.model', DirtyCarData::class);
            })
            ->whereNull('complete_messages.model_id')->get([
                'dirty_car_data.id', 
                'dirty_car_data.name', 
                'dirty_car_data.brand',
                'dirty_car_data.price', 
                'dirty_car_data.images'
            ]);

        foreach ($all as $model) {
            $current = $model->dirtyCarParametersData->filter(function ($item) use ($properties) {
                return in_array($item->property,$properties);
            });
            $model->brand = $current->first()->value;
        }

        // dd($all->groupBy(function($item){
        //     return $item->brand;
        // })->map(function($item){
        //     return $item->count();
        // })->sortDesc());

        $arrBrands = $all->groupBy(function($item){
            return $item->brand;
        });

        $groupForum = $this->telegramSettingsService->getCarForumGroup();

        foreach ($groupForum['topics'] as $topic) {
            if (isset($arrBrands[$topic['name']])) {
                $model = $arrBrands[$topic['name']]->pop();
                $message = $this->messageService->createMessageDto($model);
                $cleanParams = $this->parametersService->getCleanValues($model->dirtyCarParametersData->pluck('value', 'property'));
                $message = $this->messageService->updateMessageDto($message, $cleanParams);

                $this->senderService->sendTelegram($message, $groupForum['id'], SenderService::AUTO, $topic['id']);

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
                $message = $this->messageService->createMessageDto($model);
                $cleanParams = $this->parametersService->getCleanValues($model->dirtyCarParametersData->pluck('value', 'property'));
                $message = $this->messageService->updateMessageDto($message, $cleanParams);

                $this->senderService->sendTelegram($message, $chatId, SenderService::AUTO);
                unset($message);
                sleep(31);
            }
        }        

        $this->info('Finish');
    }
}

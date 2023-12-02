<?php

namespace App\Console\Commands;

use App\Classes\Dto\Message;
use App\Enums\SendScop;
use App\Enums\SendType;
use App\Enums\SourceType;
use App\Enums\Transport;
use App\Models\Group;
use App\Services\CarService;
use App\Services\MessageService;
use App\Services\ParametersService;
use App\Services\SenderService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

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
        private CarService $carService,
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

        $lock = Cache::get($this->signature);
        if ($lock) {
            $this->info('lock');
            return;
        }

        Cache::put($this->signature, true, 600);

        $groups = Group::where('is_active', true)->where('type', $this->type)->get();
        $topicesNames = $groups->filter(function ($group) {
            if ($group->scop === SendScop::Forum->value && !empty($group->topic_name)) {
                return $group;
            }
        })->map(function ($group) {
            return $group->topic_name;
        });

        /** @var Collection $inexpensiveGroups */
        $inexpensiveGroups = $groups->filter(function ($group) {
            if ($group->scop === SendScop::Inexpensive->value) {
                return $group;
            }
        });
        
        $isDebug = config('app.debug');
        // exclude aready senden models
        $all = $this->carService->getFreshCars($isDebug);

        // set brand key from relation to property
        $arrBrands = $this->carService->fillterByBrand($all, $topicesNames);

        // dd($this->carService->calculateBrands($all));

        $arrInexpensive = $this->carService->fillterByInexpensive($all);
        $arrInexpensiveFrom3to5 = $this->carService->fillterByInexpensive($all, 3000, 5000);
        $arrInexpensiveFrom5to10 = $this->carService->fillterByInexpensive($all, 5000, 10000);

        $this->senderService->init(SendType::Auto, Transport::Telegram, $this->type);

        try {
            $minGroup = $inexpensiveGroups->where('topic_name', '€0 - €3’000');
            $minGroup->each(function ($group) use ($arrInexpensive) {
                $this->senderService->prepareInexpensive($arrInexpensive->first(), $group);
            });
            unset($minGroup);
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        try {
            $minGroup = $inexpensiveGroups->where('topic_name', '€3’000 - €5’000');
            $minGroup->each(function ($group) use ($arrInexpensiveFrom3to5) {
                $this->senderService->prepareInexpensive($arrInexpensiveFrom3to5->first(), $group);
            });
            unset($minGroup);
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
        try {
            $minGroup = $inexpensiveGroups->where('topic_name', '€5’000 - €10’000');
            $minGroup->each(function ($group) use ($arrInexpensiveFrom5to10) {
                $this->senderService->prepareInexpensive($arrInexpensiveFrom5to10->first(), $group);
            });
            unset($minGroup);
        } catch (\Throwable $th) {
            info($th->getMessage());
        }

        // send to telegram
        foreach ($groups as $group) {
            if (isset($arrBrands[$group->topic_name])) {
                $model = $arrBrands[$group->topic_name]->pop();

                $message = $this->messageService->createMessageDto($model);
                $cleanParams = $this->parametersService->getCleanValues($model->dirtyCarParametersData->pluck('value', 'property'));
                $message = $this->messageService->updateMessageDto($message, $cleanParams);

                $dto = new Message(SendType::Auto, $group, Transport::Telegram, $message);

                $this->senderService->sendMessage($dto);
            }
        }

        Cache::forget($this->signature);

        $this->info('Finish');
    }
}

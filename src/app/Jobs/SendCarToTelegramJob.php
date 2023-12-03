<?php

namespace App\Jobs;

use App\Models\DirtyCarData;
use App\Services\MessageService;
use App\Services\ParametersService;
use App\Services\SenderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Enums\SendType;

class SendCarToTelegramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected DirtyCarData $model;
    private $chatId;
    private $topicId;
    
    /**
     * Create a new job instance.
     */
    public function __construct(
        DirtyCarData $model, 
        string $chatId,
        ?int $topicId = null,
        private SenderService $senderService,
        private MessageService $messageService,
        private ParametersService $parametersService,
    )
    {
        $this->model = $model;
        $this->chatId = $chatId;
        $this->topicId = $topicId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $message = $this->messageService->createMessageDto($this->model);
        $cleanParams = $this->parametersService->getCleanValues($this->model->dirtyCarParametersData->pluck('value', 'property'));
        $message = $this->messageService->updateMessageDto($message, $cleanParams);

        $this->senderService->sendTelegram($message, $this->chatId, SendType::Auto->value, $this->topicId);
    }
}

<?php 

namespace App\Services;

use App\Classes\Dto\Message;
use App\Classes\Messages\MessageCar;
use App\Classes\Storages\TelegramStorage;
use App\Classes\Telegram\Telegram;
use App\Enums\SendType;
use App\Enums\SourceType;
use App\Enums\Transport;
use App\Models\CompleteMessage;
use App\Models\DirtyCarData;
use App\Models\Group;
use Carbon\CarbonImmutable;

class SenderService
{
    private SendType $sendType;
    private SourceType $sourceType;
    private Transport $transport;

    function __construct(
        private MessageService $messageService,
        private TelegramStorage $telegramStorage,
        private ParametersService $parametersService,
    )
    {
    }

    public function init(SendType $sendType, Transport $transport, SourceType $sourceType): void
    {
        $this->sendType = $sendType??SendType::Auto;
        $this->transport = $transport??Transport::Telegram;
        $this->sourceType = $sourceType??SourceType::Car;
        if ($transport->value == Transport::Telegram->value) {
            $this->telegramStorage->make($this->sourceType);
        }

    }

    public function sendMessage(Message $config): void
    {
        
        $transportStorage = $this->getTransport($config);
        if ($transportStorage == null) {
            return;
        }
        if (!$transport = $transportStorage->getReady($config->target->scop)) {
            return;
        }

        CompleteMessage::create([
            'model' => $config->message->original::class,
            'model_id' => $config->message->original->id,
            'message' => $config->message::class,
            'chat' => $config->target->group_id,
            'type' => $this->sendType,
        ]);

        $transport->sendMediaMessage($config->message, $config->target->group_id, $config->target->topic);
    }

    public function sendTelegram(MessageCar $messageCar, string $chatId=null, string $type= SendType::Auto, ?int $topic= null): void
    {
        CompleteMessage::create([
            'model' => $messageCar->original::class,
            'model_id' => $messageCar->original->id,
            'message' => $messageCar::class,
            'chat' => $chatId??Telegram::TEST_CHAT_ID,
            'type' => $type,
        ]);
        $this->telegramStorage->getReady()->sendMediaMessage($messageCar, $chatId, $topic);
    }

    public function isTimeToSend(): bool
    {
        $morning = CarbonImmutable::createFromTime(9, 03, 00, 'Europe/Belgrade');
        $evening = CarbonImmutable::createFromTime(20, 03, 00, 'Europe/Belgrade');
        $now = CarbonImmutable::now('Europe/Belgrade');
        if ($now->between($morning, $evening)) {
            return true;
        }
        return false;
    }

    public function prepareInexpensive(DirtyCarData $model, Group $group): void
    {
        $message = $this->messageService->createMessageDto($model);
        $cleanParams = $this->parametersService->getCleanValues($model->dirtyCarParametersData->pluck('value', 'property'));
        $message = $this->messageService->updateMessageDto($message, $cleanParams);

        $dto = new Message(SendType::Auto, $group, Transport::Telegram, $message);
        $this->sendMessage($dto);
    }

    private function getTransport(Message $config)
    {
        switch ($config->transport->value) {
            case Transport::Telegram->value:
                return $this->telegramStorage;
            default:
                return null;
        }    
    }
}

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
use Carbon\CarbonImmutable;

class SenderService
{
    private SendType $sendType;
    private SourceType $sourceType;
    private Transport $transport;

    function __construct(
        private MessageService $messageService,
        private TelegramStorage $telegramStorage,
    )
    {
    }

    function init(SendType $sendType, Transport $transport, SourceType $sourceType): void
    {
        $this->sendType = $sendType??SendType::Auto;
        $this->sourceType = $sourceType??SourceType::Car;
        $this->transport = $transport??Transport::Telegram;
        if ($transport->value == Transport::Telegram->value) {
            $this->telegramStorage->make($this->sourceType);
        }

    }

    public function sendMessage(Message $config): void
    {
        $transportStorage = $this->getTransport($config->transport);
        if ($transportStorage == null) {
            return;
        }
        if (!$transport = $transportStorage->getReady()) {
            return;
        }

        CompleteMessage::create([
            'model' => $config->message->original::class,
            'model_id' => $config->message->original->id,
            'message' => $config->message::class,
            'chat' => $config->target[0],
            'type' => $this->sendType,
        ]);

        $transport->sendMediaMessage($config->message, $config->target[0], $config->target[1]);
    }

    function sendTelegram(MessageCar $messageCar, string $chatId=null, string $type= SendType::Auto, ?int $topic= null): void
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

    function isTimeToSend(): bool
    {
        $morning = CarbonImmutable::createFromTime(9, 03, 00, 'Europe/Belgrade');
        $evening = CarbonImmutable::createFromTime(20, 03, 00, 'Europe/Belgrade');
        $now = CarbonImmutable::now('Europe/Belgrade');
        if ($now->between($morning, $evening)) {
            return true;
        }
        return false;
    }

    private function getTransport(Transport $transport)
    {
        switch ($transport->value) {
            case Transport::Telegram->value:
                return $this->telegramStorage;
            default:
                return null;
        }    
    }
}

<?php 

namespace App\Services;

use App\Classes\Messages\MessageCar;
use App\Classes\Telegram\Telegram;
use App\Models\CompleteMessage;
use Carbon\CarbonImmutable;

class SenderService
{
    const HANDLE = 'handle';
    const AUTO = 'auto';

    function __construct(
        private MessageService $messageService,
        private Telegram       $telegram
    )
    {}

    function sendTelegram(MessageCar $messageCar, string $chatId=null, string $type= self::AUTO, ?int $topic= null): void
    {
        CompleteMessage::create([
            'model' => $messageCar->original::class,
            'model_id' => $messageCar->original->id,
            'message' => $messageCar::class,
            'chat' => $chatId??Telegram::TEST_CHAT_ID,
            'type' => $type,
        ]);
        $this->telegram->sendMediaMessage($messageCar, $chatId, $topic);
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
}

<?php 

namespace App\Services;

use App\Classes\Telegram\MessageCar;
use App\Classes\Telegram\Telegram;
use App\Models\CompleteMessage;

class SenderService
{
    const HANDLE = 'handle';
    const AUTO = 'auto';

    function __construct(
        private MessageService $messageService,
        private Telegram       $telegram
    )
    {}

    function sendTelegram(MessageCar $messageCar, string $chatId=null, $type= self::AUTO): void
    {
        CompleteMessage::create([
            'model' => $messageCar->original::class,
            'model_id' => $messageCar->original->id,
            'message' => $messageCar::class,
            'chat' => $chatId??Telegram::TEST_CHAT_ID,
            'type' => $type,
        ]);
        $this->telegram->sendMediaMessage($messageCar, $chatId);
    }
}

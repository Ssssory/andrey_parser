<?php 

namespace App\Classes\Telegram;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

final class Telegram
{
    // const TEST_CHAT_ID = -925022442;
    const TEST_CHAT_ID = -1001592144637;

    private Nutgram $client;

    public function __construct(Client $client) {
        $this->client = $client->getClient();
    }

    public function sendTextMesage(string $text): void
    {
        $this->client->sendMessage(
            text: $text,
            chat_id: self::TEST_CHAT_ID
        );
    }

    function sendOnePhotoMesage(string $urlPhoto): void
    {
        $this->client->sendPhoto(
            photo: InputFile::make($urlPhoto),
            chat_id: self::TEST_CHAT_ID
        );
    }

    function sendMediaMessage(MessageInterface $message, string $chatId=null): void
    {
        $this->client->sendMediaGroup(
            $message->getMessage(),
            $chatId ?? self::TEST_CHAT_ID
        );
    }
}

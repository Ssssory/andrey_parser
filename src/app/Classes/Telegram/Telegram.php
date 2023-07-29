<?php 

namespace App\Classes\Telegram;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

final class Telegram
{
    const TEST_CHAT_ID = -925022442;

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

    function sendRentMessage(Message $message): void
    {
        $this->client->sendMediaGroup(
            $message->getMessage(),
            self::TEST_CHAT_ID
        );
    }
}

<?php 

namespace App\Classes\Telegram;

use App\Classes\Contracts\MessageInterface;
use App\Classes\Contracts\TransportInterface;
use SergiX44\Nutgram\Telegram\Types\Chat\Chat;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

final class Telegram implements TransportInterface
{
    const TEST_CHAT_ID = -1001592144637;

    private Client $client;

    public function __construct(string $token)
    {
        $this->client = new Client($token);
    }

    public function sendTextMesage(string $text, string $chatId, int $thread): void
    {
        $this->client->getClient()->sendMessage(
            $text,
            $chatId,
            $thread
        );
    }

    function getChat(string $chatId): Chat|null 
    {
        return $this->client->getClient()->getChat($chatId);
    }

    function sendOnePhotoMesage(string $urlPhoto): void
    {
        $this->client->getClient()->sendPhoto(
            photo: InputFile::make($urlPhoto),
            chat_id: self::TEST_CHAT_ID
        );
    }

    function sendMediaMessage(MessageInterface $message, string $chatId=null, int $topic=null): void
    {
        $this->client->getClient()->sendMediaGroup(
            $message->getMessage(),
            $chatId ?? self::TEST_CHAT_ID,
            $topic
        );
    }
}

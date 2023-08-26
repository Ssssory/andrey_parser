<?php 

namespace App\Classes\Telegram;

use App\Classes\Messages\MessageInterface;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

final class Telegram
{
    // const TEST_CHAT_ID = -925022442;
    const TEST_CHAT_ID = -1001592144637;

    private Nutgram $client;
    private Client  $proxyClient;

    public function __construct(Client $client) {
        $this->client = $client->getClient();
        $this->proxyClient = $client;
    }

    public function sendTextMesage(string $text,string $chatId,int $thread): void
    {
        $this->client->sendMessage(
            $text,
            $chatId ?? self::TEST_CHAT_ID,
            $thread
        );
    }

    function getChat(string $chatId) 
    {
        return $this->client->getChat($chatId);
    }

    function sendOnePhotoMesage(string $urlPhoto): void
    {
        $this->client->sendPhoto(
            photo: InputFile::make($urlPhoto),
            chat_id: self::TEST_CHAT_ID
        );
    }

    function sendMediaMessage(MessageInterface $message, string $chatId=null, int $topic=null): void
    {
        $this->proxyClient->getNewClient()->sendMediaGroup(
            $message->getMessage(),
            $chatId ?? self::TEST_CHAT_ID,
            $topic
        );
    }
}

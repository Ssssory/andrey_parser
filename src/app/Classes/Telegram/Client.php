<?php 

namespace App\Classes\Telegram;

use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;

final class Client
{
    private ?Nutgram $client = null;
    private ?string $token = null;

    function __construct(string $token)
    {
        $this->token = $token;
        $config = $this->getConfig();
        $this->client = new Nutgram($this->token, $config);
    }

    public function getClient(): Nutgram {
        return $this->client;
    }

    public function reconnect(): Nutgram
    {
        $config = $this->getConfig();
        $this->client = new Nutgram($this->token, $config);
        return $this->client;
    }

    private function getConfig() : Configuration {
        return new Configuration(
            clientTimeout: 20,
        );
    }
}

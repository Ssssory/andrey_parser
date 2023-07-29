<?php 

namespace App\Classes\Telegram;

use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;

final class Client
{
    private ?Nutgram $client = null;


    function __invoke()
    {
        return $this->getClient();
    }

    public function getClient(): Nutgram {
        if (!$this->client) {
            $config = new Configuration(
                clientTimeout: 20,
            );
            $this->client = new Nutgram(env('TELEGRAM_TOKEN'), $config);
        }
        return $this->client;
    }
}

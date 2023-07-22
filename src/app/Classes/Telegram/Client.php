<?php 

namespace App\Classes\Telegram;

use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;

final class Client
{
    private ?Nutgram $client = null;

    const TOKEN = '6603078627:AAHbofAmoe_uV8B-RseAVkKFDa4-adwXP7E';

    function __invoke()
    {
        return $this->getClient();
    }

    public function getClient(): Nutgram {
        if (!$this->client) {
            $config = new Configuration(
                clientTimeout: 20,
            );
            $this->client = new Nutgram(self::TOKEN, $config);
        }
        return $this->client;
    }
}

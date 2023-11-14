<?php 

namespace App\Classes\Items;

use App\Classes\Telegram\Telegram;
use App\Enums\SendScop;
use App\Enums\SourceType;
use Carbon\Carbon;

class TelegramStorageItem
{
    private string $token;
    private SourceType $type;
    private ?Carbon $lastUsed = null;
    private Telegram $client;
    private ?SendScop $scop = null;

    public function __construct(string $token, SourceType $type, ?SendScop $scop) {
        $this->token = $token;
        $this->type = $type;
        $this->scop = $scop;
        $this->client = new Telegram($token);
    }

    public function getType(): SourceType {
        return $this->type;
    }

    public function getScop(): ?SendScop {
        return $this->scop;
    }

    function sendTextMesage($text, $chatId, $topic) 
    {
        $this->lastUsed = Carbon::now();
        $this->client->sendTextMesage($text,$chatId, $topic);
    }

    function sendMediaMessage($message, $chatId, $topic) 
    {
        $this->lastUsed = Carbon::now();
        $this->client->sendMediaMessage($message, $chatId, $topic);
    }

    function getLastUsed()
    {
        return $this->lastUsed??Carbon::now()->subSeconds(100);
    }

    function __toString()
    {
        return sprintf('s% s% s%', $this->token, $this->type->value, $this->lastUsed);
    }

}
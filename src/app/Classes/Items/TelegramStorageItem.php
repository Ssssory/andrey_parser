<?php 

namespace App\Classes\Items;

use App\Classes\Contracts\MessageInterface;
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

    public function getToken(): string {
        return $this->token;
    }

    public function getType(): SourceType {
        return $this->type;
    }

    public function getScop(): ?SendScop {
        return $this->scop;
    }

    function sendTextMesage(string $text, string $chatId, int $topic): void
    {
        $this->lastUsed = Carbon::now();
        $this->client->sendTextMesage($text,$chatId, $topic);
    }

    function sendMediaMessage(MessageInterface $message, string $chatId, int $topic): void
    {
        $this->lastUsed = Carbon::now();
        $this->client->sendMediaMessage($message, $chatId, $topic);
    }

    function getLastUsed(): Carbon
    {
        return $this->lastUsed??Carbon::now()->subSeconds(100);
    }
}
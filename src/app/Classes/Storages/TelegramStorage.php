<?php 

namespace App\Classes\Storages;

use App\Classes\Items\TelegramStorageItem;
use App\Enums\SourceType;
use App\Models\Bot;
use Illuminate\Support\Collection;

final class TelegramStorage
{
    private Collection $clients;
    private ?SourceType $type = null;
    private $period = 58;

    public function __construct() {
        $this->clients = new Collection();
    }

    public function make(SourceType $type): void {
        if ($this->type && $this->type->value != $type->value) {
            throw new \Exception("Type mismatch");
        }
        $this->type = $type;
        if ($this->clients->isEmpty()) {
            Bot::where('is_active', true)->where('type', SourceType::Car)->get()->each(function($bot) {
                $this->clients->put($bot->name, new TelegramStorageItem($bot->token, $this->type));
            });
        }
    }

    public function add(string $token, string $name): void {
        $this->clients->put($name, new TelegramStorageItem($token, $this->type));
    }

    function remove(string $name) : void {
        $this->clients->forget($name);
    }

    function getCount() : int 
    {
        return $this->clients->count();
    }   

    function getAll()
    {
        return $this->clients;    
    }

    function getReady() : ?TelegramStorageItem
    {
        foreach ($this->clients as $client) {
            if ($client->getLastUsed() < now()->subSeconds($this->period)) {
                return $client;
            }
        }
        return null;
    }

    private function getMinimumTimer() {
        $client = $this->clients->min(function($client) {
            return $client->getLastUsed();
        });
        if ($client->getLastUsed > now()->subMinute()) {
            return $client->getLastUsed();
        }
    }
}

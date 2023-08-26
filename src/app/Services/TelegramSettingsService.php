<?php 

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

class TelegramSettingsService
{
    private array $settings = [];
    private string $filename = 'settings.json';

    const LOCAL_BOT_NAME = 'rentman';

    public function __construct()
    {
        if (!Storage::disk('local')->exists($this->filename)) {
            return;
        }
        $this->settings = json_decode(Storage::disk('local')->get($this->filename), true);
    }

    public function getJson() : string {
        if (Storage::disk('local')->exists($this->filename)) {
            return Storage::disk('local')->get($this->filename);
        }
        return '';
    }

    public function saveJson(string $json): bool
    {
        return Storage::disk('local')->put($this->filename, $json);
    }

    public function getTestGroup() : array
    {
        if (isset($this->settings[self::LOCAL_BOT_NAME]['groups']['rent_test']) === false) {
            throw new Exception("Local function only", 500);
        }
        return $this->settings[self::LOCAL_BOT_NAME]['groups']['rent_test'];
    }

    public function getCarForumGroup() : array
    {
        if (isset($this->settings[self::LOCAL_BOT_NAME]['groups']['Serbia Auto Forum']) === false) {
            throw new Exception("Not defined forum", 500);
        }
        return $this->settings[self::LOCAL_BOT_NAME]['groups']['Serbia Auto Forum'];
    }

    public function getCarGroup() : array
    {
        if (isset($this->settings[self::LOCAL_BOT_NAME]['groups']['Serbia Auto']) === false) {
            throw new Exception("Not defined group", 500);
        }
        return $this->settings[self::LOCAL_BOT_NAME]['groups']['Serbia Auto'];
    }


}
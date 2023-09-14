<?php

namespace App\Classes\Messages;

use App\Classes\Contracts\MessageInterface;
use Exception;
use SergiX44\Nutgram\Telegram\Types\Input\InputMediaPhoto;

/**
 * Class MessageRent
 * @package App\Classes\Messages
 * @property string $id
 * @property array $tags
 * @property array $images set by setImages()
 * @property bool $deposit
 * @property string $price
 * @property string $type
 * @property string $square
 * @property string $floor
 * @property string $rooms
 * @property string $pets
 * @property string $location
 * 
 */
final class MessageRent implements MessageInterface
{
    public ?string $id = null;
    public array $tags = [];
    private array $images = [];
    // public string $description = '';
    public bool $deposit = false;

    public ?string $price = null;
    public ?string $type = 'rent';
    public ?string $square = null;
    public ?string $floor = null;
    public ?string $rooms = null;
    public ?string $pets = null;
    public ?string $location = null;
    
    
    private string $signature = 'Agent: RU/EN @BLGconsult Telegram. +381 62 938 4438 (call)';

    public function setImages(array $images): void
    {
        $count = 0;
        foreach ($images as $image) {
            $this->images[] = $image;
            $count++;
            if ($count == 10) {
                break;
            }
        }
    }

    function getImages(): array
    {
        return $this->images;
    }

    function getId(): string 
    {
        if (!$this->id) {
            return substr(md5(uniqid()), 0, 8);
        }
        return $this->id;
    }

    function getTags(): string 
    {
        return trim(implode(' #', $this->tags)); 
    }

    function getType(): string 
    {
        if ($this->type == 'rent') {
            return '#rent #аренда';
        }elseif ($this->type == 'sell') {
            return '#sell #продажа';
        }
        return '';
    }


    private function getDescription() : string 
    {
    
        $description = '#' . $this->getId() . PHP_EOL;
        $description .= $this->getTags(). PHP_EOL;
        if ($this->type == 'rent') {
            $description .= '#rent  #аренда' . PHP_EOL;
        }
        if ($this->price) {
            $description .= 'Цена: ' . $this->price . PHP_EOL;
        }
        if ($this->square) {
            $description .= 'Площадь: ' . $this->square . PHP_EOL;
        }
        if ($this->floor) {
            $description .= 'Этаж: ' . $this->floor . PHP_EOL;
        }
        if ($this->rooms) {
            $description .= 'Тип планировки: ' . $this->rooms . PHP_EOL;
        }
        if ($this->pets) {
            $description .= $this->pets . PHP_EOL;
        }
        if ($this->location) {
            $description .= 'Расположение: ' . $this->location . PHP_EOL;
        }
        $description .= PHP_EOL . PHP_EOL . PHP_EOL;

        if ($this->deposit) {
            $description .= $this->price . ' Депозит. Комиссия агентства 50%' . PHP_EOL;
        }
        $description .= $this->signature;

        return $description;
    }

    public function getMessage(): array
    {
        $answer = [];
        foreach ($this->images as $image) {
            $answer[] = InputMediaPhoto::make($image);
        }
        $last = array_pop($answer);
        $capture = $this->getDescription();
        if (strlen($capture) > 1024) {
            throw new Exception("so match lenth of capture", 500);
        }
        $last->caption = $this->getDescription();

        return array_merge($answer, [$last]);
    }

    public function getMessageFullText(): string
    {
        return $this->getDescription();
    }

}

<?php

namespace App\Classes\Messages;

use Exception;
use Illuminate\Database\Eloquent\Model;
use SergiX44\Nutgram\Telegram\Types\Input\InputMediaPhoto;

/**
 * Class MessageCar
 * @package App\Classes\Messages
 * @property string $id
 * @property array $tags
 * @property array $images set by setImages()
 * @property string $name
 * @property string $description
 * @property string $model
 * @property string $year
 * @property string $mileage
 * @property string $engineType
 * @property string $engineVolume
 * @property string $transmission
 * @property string $price
 * 
 */
final class MessageCar implements MessageInterface
{
    public ?string $id = null;
    public ?Model $original = null;
    public array $tags = [];
    private array $images = [];
    public string $name = '';
    public string $url = '';
    public ?string $description = '';
    public ?string $model = '';
    public ?string $year = '';
    public ?string $mileage = '';
    public ?string $engineType = '';
    public ?string $engineVolume = '';
    public ?string $transmission = '';
    public ?string $price = '';

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

    private function getDescription() : string 
    {
        $description = '🚘 ' . $this->name . PHP_EOL;
        $description .=  PHP_EOL . 'Модель: ' . $this->model . PHP_EOL;
        if ($this->year) {
            $description .= 'Год выпуска: ' . $this->year . PHP_EOL;
        }
        if ($this->mileage) {
            $description .= 'Пробег: ' . $this->mileage . PHP_EOL;
        }
        if ($this->engineType) {
            $description .= 'Тип мотора: ' . $this->engineType . PHP_EOL;
        }
        if ($this->engineVolume) {
            $description .= 'Объём мотора: ' . $this->engineVolume . PHP_EOL;
        }
        if ($this->transmission) {
            $description .= 'Тип коробки: ' . $this->transmission . PHP_EOL;
        }
        if ($this->url) {
            $description .= 'Ссылка на объявление: ' . $this->url . PHP_EOL;
        }
        if ($this->price) {
            $description .= PHP_EOL . '💰 Цена: ' . $this->price . PHP_EOL;
        }
        $description .= PHP_EOL . 'ID: ' . $this->getId() . PHP_EOL;

        $description .= PHP_EOL . $this->getTags() . PHP_EOL;

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
}

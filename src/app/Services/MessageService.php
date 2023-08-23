<?php

namespace App\Services;

use App\Classes\Telegram\MessageCar;

final class MessageService
{
    function updateMessageDto(MessageCar $message, array $data): MessageCar {
        foreach ($data as $oneParameter) {
            if ($oneParameter['valid']) {
                if ($oneParameter['name'] == 'engin_type') {
                    $message->engineType = $oneParameter['value'];
                }
                if ($oneParameter['name'] == 'model') {
                    $message->model = $oneParameter['value'];
                }
                if ($oneParameter['name'] == 'year') {
                    $message->year = $oneParameter['value'];
                }
                if ($oneParameter['name'] == 'mileage') {
                    $message->mileage = $oneParameter['value'];
                }
                if ($oneParameter['name'] == 'engine_volume') {
                    $message->engineVolume = $oneParameter['value'];
                }
                if ($oneParameter['name'] == 'transmission') {
                    $message->transmission = $oneParameter['value'];
                }
            }
        }
        return $message;
    }
}
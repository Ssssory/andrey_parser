<?php

namespace App\Services;

use App\Classes\Messages\MessageCar;
use App\Models\DirtyCarData;
use Carbon\Carbon;
use Illuminate\Http\Request;

final class MessageService
{
    /**
     * @param MessageCar $message
     * @param array $data
     * @return MessageCar
     */
    public function updateMessageDto(MessageCar $message, array $data): MessageCar {
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

    /**
     * @param Request $request
     * @param DirtyCarData $model
     * @return MessageCar
     */
    public function getCarMessage(Request $request, DirtyCarData $model) : MessageCar 
    {
        $message = new MessageCar();
        $message->id = $request->input('id');
        $message->original = $model;
        $message->tags = explode(' ', $request->input('tags', ''));
        $message->price = $request->input('price');
        $message->setImages(explode(',', $model->images));
        $message->name = $request->input('name');
        $message->model = $request->input('model');
        $message->year = $request->input('year');
        $message->mileage = $request->input('mileage');
        $message->engineType = $request->input('engineType');
        $message->engineVolume = $request->input('engineVolume');
        $message->transmission = $request->input('transmission');

        return $message;
    }

    /**
     * @param DirtyCarData $model
     * @return string
     */
    public function getMessageCarId(DirtyCarData $model): string
    {
        return Carbon::now()->format('my') . str_pad($model->id, 5, 0, STR_PAD_LEFT);
    }
}
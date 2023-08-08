<?php 

namespace App\Http\Controllers;

use App\Classes\Telegram\MessageCar;
use App\Classes\Telegram\Telegram;
use App\Enums\Sources;
use App\Models\DirtyCarData;
use App\Models\DirtyCarParametersData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function list(string $model)
    {
        if (!$model) {
            throw new Exception("Error Processing Request");
        }

        $source = Sources::from($model);
        if ($model == Sources::Polovniautomobili->value) {
            $list = DirtyCarParametersData::join('dirty_car_data', 'dirty_car_data.id', '=', 'dirty_car_parameters_data.car_id')
                ->where('dirty_car_data.source', $source->name)
                ->where('dirty_car_parameters_data.property', 'Broj oglasa:')
                ->orderByDesc('dirty_car_parameters_data.value')
                ->select('dirty_car_data.*')
                ->paginate(15);
        }else{
            $list = DirtyCarData::where('source', $source->name)->orderByDesc('id')->paginate(15);
        }
        $count = DirtyCarData::where('source', $source->name)->count();

        $engineTypes = DirtyCarParametersData::where('property', 'Gorivo')->distinct()->get(['value']);

        return view('pages.car.table', [
            'title' => $model,
            'list' => $list,
            'count' => $count,
            'fillter' => [
                'engineTypes' => $engineTypes->pluck('value'),
            ]
        ]);
    }

    function form(Request $request, DirtyCarData $model)
    {
        $message = new MessageCar();
        $message->id = Carbon::now()->format('my') . str_pad($model->id, 5, 0, STR_PAD_LEFT);

        $message->setImages(explode(',', $model->images));
        $message->price = $model->price;
        $message->name = $model->name;


        $model->load('dirtyCarParametersData');

        return view('pages.car.telegram-form', [
            'title' => 'Send to telegramm',
            'model' => $model,
            'message' => $message,
        ]);
    }

    function send(Request $request, DirtyCarData $model, Telegram $telegram)
    {
        $message = new MessageCar();
        $message->id = $request->input('id');
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
        // dd($message->getMessage());

        $telegram->sendMediaMessage($message);

        try {
            return redirect()->route('car.list', ['model' => $model->source])->with('message', 'success');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('message', 'error');
        }
    }
}
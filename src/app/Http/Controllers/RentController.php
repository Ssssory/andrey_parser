<?php 

namespace App\Http\Controllers;

use App\Classes\Telegram\MessageRent;
use App\Classes\Telegram\Telegram;
use App\Enums\Sources;
use App\Models\DirtyStateData;
use App\Models\DirtyStateParametersData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

final class RentController extends Controller
{
    function list(Request $request, string $model)
    {
        if (!$model) {
            throw new Exception("Error Processing Request");
        }
        $source = Sources::from($model);
        $list = DirtyStateData::where('source', $source->name)->limit(100)->orderByDesc('id')->get();
        $count = DirtyStateData::where('source', $source->name)->count();

        return view('pages.rent.table', [
            'title' => $model,
            'list' => $list,
            'count' => $count,
        ]);
    }

    function form(Request $request, DirtyStateData $model) 
    {
        //dd($model);
        $message = new MessageRent();
        $message->id = Carbon::now()->format('my') . substr(strval($model->id / 100000), 2, -1);
        // $message->tags = $model->tags;
        $message->setImages(explode(',',$model->images));
        // $message->deposit = $model->deposit;
        $message->price = $model->price;
        // $message->type = $model->type;
        // $message->square = $model->square;
        // $message->floor = $model->floor;
        // $message->rooms = $model->rooms;
        // $message->pets = $model->pets;
        $message->location = $model->address;

        $model->load('dirtyStateParametersData');

        return view('pages.rent.telegram-form', [
            'title' => 'Send to telegramm',
            'model' => $model,
            'message' => $message,
        ]);
    }

    function send(Request $request, DirtyStateData $model, Telegram $telegram)
    {
        $message = new MessageRent();
        $message->id = $request->input('id');
        $message->tags = explode(' ', $request->input('tags',''));
        $message->price = $request->input('price');
        $message->square = $request->input('square');
        $message->rooms = $request->input('rooms');
        $message->location = $request->input('location');
        $message->setImages(explode(',', $model->images));
        // dd($message->getMessage());

        $telegram->sendMediaMessage($message);

        try {
            return redirect()->route('rent.list',['model' => $model->source])->with('message', 'success');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->with('message', 'error');
        }


        // 
    }
}

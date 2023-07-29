<?php 

namespace App\Http\Controllers;

use App\Classes\Telegram\Message;
use App\Enums\Sources;
use App\Models\DirtyStateData;
use App\Models\DirtyStateParametersData;
use Carbon\Carbon;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

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

        return view('pages.rent-table', [
            'title' => $model,
            'list' => $list,
            'count' => $count,
        ]);
    }

    function form(Request $request, DirtyStateData $model) 
    {
        //dd($model);
        $message = new Message();
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

        return view('pages.telegram-form', [
            'title' => 'Send to telegramm',
            'model' => $model,
            'message' => $message,
        ]);
    }
}

<?php 

namespace App\Http\Controllers;

use App\Enums\Sources;
use App\Models\DirtyStateData;
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
        dd($model);
    }
}

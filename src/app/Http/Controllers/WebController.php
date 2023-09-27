<?php

namespace App\Http\Controllers;

use App\Models\DirtyCarData;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function home() 
    {
        $cars = DirtyCarData::with('dirtyCarParametersData')->orderBy('id', 'desc')->limit(4)->get();
        // dd($cars->first()->dirtyCarParametersData->filter(function($value){return $value->property == 'Kilometraža';})->first()->value);
        return view('web.homepage',[
            'cars' => $cars
        ]);
    }
}

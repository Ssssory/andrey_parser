<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestController extends Controller
{
    public function index(Request $request)
    {

        return view('pages.test', [
            'url' => ''
        ]);
    }

    

}
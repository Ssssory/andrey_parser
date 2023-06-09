<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', [Controller::class, 'index']);
Route::get('/list/{model}', [Controller::class, 'list']);
Route::get('/new', [Controller::class, 'startPage']);
Route::get('/test', [Controller::class, 'test']);
Route::get('/save', [Controller::class, 'saveCsv']);

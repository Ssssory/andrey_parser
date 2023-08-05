<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\TestController;
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

Route::get('/login', function(){
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'authenticate']);

// Route::get('/registration', function(){
//     return view('auth.registration');
// });

Route::get('/', function(){
    return view('landing');
});

Route::get('/test', [TestController::class, 'index']);


Route::group(['middleware' => 'auth'], function(){
    Route::get('/dashboard', [Controller::class, 'index'])->name('dashboard');

    Route::get('/list/{model}', [Controller::class, 'list']);
    Route::get('/new', [Controller::class, 'startPage']);
    Route::get('/save', [Controller::class, 'saveCsv']);

    Route::match(array('GET', 'POST'), '/temp', [TestController::class, 'index']);

    Route::get('/rent/{model}', [RentController::class, 'list'])->name('rent.list');
    Route::get('/rent/form/{model}', [RentController::class, 'form']);
    Route::post('/rent/form/{model}/send', [RentController::class, 'send']);

    Route::get('/car/{model}', [CarController::class, 'list'])->name('car.list');
    Route::get('/car/form/{model}', [CarController::class, 'form']);
    Route::post('/car/form/{model}/send', [CarController::class, 'send']);

    Route::prefix('admin')->group(function (){
        Route::get('/users', [AdminUserController::class, 'users']);
        Route::get('/users/new', [AdminUserController::class, 'newUser']);
        Route::post('/users/new', [AdminUserController::class, 'createUser']);
        // Route::get('/users/{id}', [AdminUserController::class, 'user']);
        // Route::get('/users/{id}/edit', [AdminUserController::class, 'editUser']);
        // Route::post('/users/{id}/edit', [AdminUserController::class, 'updateUser']);
        // Route::get('/users/{id}/delete', [AdminUserController::class, 'deleteUser']);
        Route::match(array('GET', 'POST'), '/settings/telegram', [AdminController::class, 'settingsTelegram']);
    });
});


<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use Illuminate\Routing\RouteGroup;
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
// Route::get('/users/new', [AdminUserController::class, 'newUser']);
// Route::post('/users/new', [AdminUserController::class, 'createUser']);
Route::get('/test', [Controller::class, 'test']);


Route::group(['middleware' => 'auth'], function(){
    Route::get('/dashboard', [Controller::class, 'index'])->name('dashboard');
    Route::get('/list/{model}', [Controller::class, 'list']);
    Route::get('/rent/{model}', [Controller::class, 'rentList']);
    Route::get('/new', [Controller::class, 'startPage']);
    Route::get('/save', [Controller::class, 'saveCsv']);

    Route::prefix('admin')->group(function (){
        Route::get('/users', [AdminUserController::class, 'users']);
        Route::get('/users/new', [AdminUserController::class, 'newUser']);
        Route::post('/users/new', [AdminUserController::class, 'createUser']);
        // Route::get('/users/{id}', [AdminUserController::class, 'user']);
        // Route::get('/users/{id}/edit', [AdminUserController::class, 'editUser']);
        // Route::post('/users/{id}/edit', [AdminUserController::class, 'updateUser']);
        // Route::get('/users/{id}/delete', [AdminUserController::class, 'deleteUser']);
    });
});





Route::get('/welcome', function () {
    return view('welcome');
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/




Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');  
});

// Users
Route::group([
    //'middleware' => 'api',
    'middleware' => 'jwt.auth',
    'prefix' => 'users'

], function ($router) {
    Route::get('/', [UserController::class, 'index'])->name('users');
    //Route::post('/', [AuthController::class, 'store'])->name('new');
    //Route::get('/{id}', [AuthController::class, 'show'])->name('users');
    //Route::put('/{id}', [AuthController::class, 'update'])->name('users');
    //Route::delete('/{id}', [AuthController::class, 'destroy'])->name('users');  
    }
);


// Если нет пути, то выводим ошибку
Route::fallback(function () {
    return response()->json(['message' => 'Page Not Found.'], 404);
});
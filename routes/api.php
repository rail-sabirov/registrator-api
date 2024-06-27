<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    //'middleware' => 'jwt.auth',
], function ($router) {
    /* root routes / --------------------------------- */
    Route::get('/', [ApiController::class, 'index'])->name('about');

    /* /auth/ --------------------------------- */
    Route::group([
        'prefix' => 'auth'
    ], function ($router) {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/email-verify', [AuthController::class, 'emailVerify'])->name('email-verify');  

        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/me', [AuthController::class, 'me'])->name('me');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');  
    });

    /* /users/ --------------------------------- */
    Route::group([
        'prefix' => 'users'
    ], function ($router) {
        Route::post('/', [UserController::class, 'index'])->name('users');    
    });
    
});



// Если нет введенного пути, то выводим ошибку
Route::fallback(function () {
    return response()->json(['message' => 'Page Not Found.'], 404);
});
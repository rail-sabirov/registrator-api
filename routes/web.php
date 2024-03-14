<?php

use App\Http\Controllers\Auth\RegisterController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::view('/', 'welcome')->name('welcome');
Route::view('/login', 'login')->name('login');

Route::get('/register', [RegisterController::class, 'create'])
    // если пользователь уже зарегистрирован, то перенаправляем на главную страницу
    ->middleware('guest')
    ->name('register');
// Роут для передачи данных их формы регистрации для создания пользователя в базе
Route::post('/register', [RegisterController::class, 'store'])
    // если пользователь уже зарегистрирован, то перенаправляем на главную страницу
    ->middleware('guest');


// В эти роуты будут доступны только зарегистрированные пользователи
Route::view('/dashboard', 'dashboard')->middleware('auth')->name('dashboard');
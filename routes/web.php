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

Route::get('/', 'welcome');
Route::view('/', 'welcome')->name('welcome');

// Группа только для гостей / не зарегистрированных пользователей
Route::middleware(['guest'])->group(function () {
    // Роут для отображения формы регистрации
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    // Роут для передачи данных из формы регистрации для создания пользователя в базе
    Route::post('/register', [RegisterController::class, 'store']);

    // Аутентификация пользователя / Вход
    Route::view('/login', 'login')->name('login');
});


// В эти роуты будут доступны только зарегистрированные пользователи
Route::view('/dashboard', 'dashboard')->middleware('auth')->name('dashboard');
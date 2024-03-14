<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request) 
    {
        // проверяем валидность данных полученных из формы регистрации
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => ['required', 'confirmed', 'min:6']
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // После регистрации сразу логинимся как пользователь
        Auth::login($user);

        // После логина перенаправляем на главную страницу
        // return redirect()->route('dashboard');
        // или
        // перенаправляем на главную страницу dashboard через routeServiceProvider
        return redirect()->intended(RouteServiceProvider::HOME); 
        
    }
}

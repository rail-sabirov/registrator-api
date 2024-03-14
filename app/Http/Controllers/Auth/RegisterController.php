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

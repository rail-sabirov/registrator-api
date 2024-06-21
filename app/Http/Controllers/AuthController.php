<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    // --- Регистрация
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // confirmed - подтверждение пароля
        ]);

        // Вывод ошибок при валидации полей [поле => сообщение]
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // Создание пользователя, все поля берутся из валидатора
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    // --- Авторизация
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Аутентификация пользователя
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken('User successfully logged in', $token);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email
        ];
        return response()->json($user);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $userName = (string) auth()->user()->name; 
        auth()->logout();

        return response()->json(['message' => "User ($userName) successfully signed out"]);
    }   

    // --- Обновление токена
    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = JWTAuth::getToken();
        return $this->respondWithToken('Token successfully refreshed',JWTAuth::refresh($token));
    }

    /**
     * 
     * 
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($message, $token)
    {
        $expiresMinutes = JWTAuth::factory()->getTTL() * 60;
        $expiresAt = Carbon::now()->addMinutes($expiresMinutes)->toDateTimeString();

        return response()->json([
            'message' => $message,
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_min' => JWTAuth::factory()->getTTL() * 60,
                'expires_date' => $expiresAt
            ]
        ]);
    }

}
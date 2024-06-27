<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;


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

        $user->generateVerificationCode();

        return response()->json([
            'message' => 'User successfully registered. Please check your email for the verification code.',
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $result = [
                'name' => $user->name,
                'email' => $user->email
            ];
            
            return response()->json($result);
        
        } catch (TokenExpiredException $e) {
            return response()->json([
                'message' => 'token expired'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'message' => 'token invalid'
            ], 401);    
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'message' => 'token blacklisted'
            ], 401);
        }
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


    // --- Подтверждение почты с отправкой кода на почту
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function emailVerify(Request $request)
    {
        // Проверка полей формы 
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|integer',
        ]);


        // Вывод ошибки при валидации формы
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }   

        // Поиск пользователя
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }

        $verificationCode = $user->verificationCode->code;
        if (!$verificationCode) {
            return response()->json(['message' => 'Code not found!'], 404);
        }

        // Проверка кода
        if ($verificationCode != $request->code) {
            return response()->json([
                'message' => 'Wrong code!',
                'data' => [
                    'code' => $verificationCode,
                    'request' => $request->code
                ]
            ], 404);
        }
        
        // Подтверждение почты
        $user->email_verified_at = Carbon::now();
        $user->save();

        // Удаление кода
        $user->verificationCode()->delete();

        return response()->json(['message' => 'Email successfully verified!']);
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
        
        $user = auth()->user();
        
        if($user['email_verified_at'] == null) {
            return response()->json([
                'message' => 'Email not verified',
            ], 403);
        }

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
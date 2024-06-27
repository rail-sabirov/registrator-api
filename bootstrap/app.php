<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    ->withMiddleware(function (Middleware $middleware) {
        //$middleware->append(\App\Http\Middleware\VerifyEmail::class);
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        // Отлавливаем исключение при переходе на страницы /api/* без авторизации/передачи токена
        // вместо переброса на страницу автор, выводим ошибку, у нас же API
        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->is('api/*')) {

                // Token
                if ($e instanceof TokenBlacklistedException || 
                        $e instanceof TokenExpiredException || 
                        $e instanceof TokenInvalidException) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], 403);
                }

                // URL
                if ($e instanceof MethodNotAllowedHttpException) {
                    return response()->json([
                        'message' => "Method `{$request->path()}` not allowed",
                    ], 405);
                }

                
                return response()->json([
                    'message' => $e->getMessage(),
                    //'errors' => $e->errors(),
                ], 401);
            }
        });
       
    })
    ->create();

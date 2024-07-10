<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

use Log;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*
        if($request->header('Authorization')) {
            try {
                $userFromToken = JWTAuth::parseToken()->authenticate();
        
            } catch (TokenExpiredException $e) {
                return response()->json([
                    'message' => "Token Expired1"
                ], 401);

            } catch (TokenInvalidException $e) {
                return response()->json([
                    'message' => "Token Invalid"
                ], 401);    

            } catch (TokenBlacklistedException $e) {
                return response()->json([
                    'message' => 'Token Blacklisted'
                ], 401);
            }
        }
*/
        return $next($request);
    }
}

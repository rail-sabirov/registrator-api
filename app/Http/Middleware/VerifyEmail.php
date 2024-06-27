<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\User;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $req = $request->input();
        if(array_key_exists('email', $req)) {
            $usr = User::where('email', $req['email'])->first();
             
            return response()->json([
                'message' => 'Email not verified.',
                'data' => $usr
            ], 403);    
        }
        
        if (!$request->user() instanceof MustVerifyEmail) {
            return response()->json([
                'message' => 'Email not verified.',
                'data' => [$request->getAcce, $next]
            ], 403);
        }
        
        return $next($request);
    }
}

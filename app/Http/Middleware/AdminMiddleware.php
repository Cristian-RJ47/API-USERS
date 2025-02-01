<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if($user->role !== 'admin') {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
            }
        
        } catch(Exception $e) {
            if($e instanceof TokenInvalidException) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Token invalid'
                ], 401);
            } else if($e instanceof TokenExpiredException) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Token expired'
                ], 401);
            } else {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
            }
        }

        return $next($request);
    }
}

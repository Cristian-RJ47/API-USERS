<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMilddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch(Exception $e){

            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'response'=>'error',
                    'message'=>'token invalid'
                ],401);
            }else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    'response'=>'error',
                    'message'=>'token expired'
                ],401);
            }else{
                return response()->json([
                    'response'=>'error',
                    'message'=>'token not found'
                ],401);
            }
            
            return response()->json([
                'response'=>'error',
                'message'=>'unauthorized'
            ],401);
        }

        return $next($request);
    }
}

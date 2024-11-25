<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = JWTAuth::getToken();
            \Log::info('Token:', ['token' => $token]);

            if (!JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'error' => 'Unauthorized access. User not found.',
                ], 401);
            }
        } catch (JWTException $e) {
            \Log::error('JWTException:', ['error' => $e->getMessage()]);            
            return response()->json([
                'error' => 'Token invalid or expired. Please log in again.',
            ], 401);
        }        

        return $next($request);
    }

}

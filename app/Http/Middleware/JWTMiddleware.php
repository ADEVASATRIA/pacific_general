<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user || $user->role_id != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot Access, Only Super Admin Can Access This Feature!'
                ], 403);
            }
            
            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'error' => $e->getMessage(),
            ], 401);
        }
    }
}


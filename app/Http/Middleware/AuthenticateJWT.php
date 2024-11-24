<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthenticateJWT
{
    public function handle($request, Closure $next)
    {
        try {
            // Verifica se o token é válido
            $usuario = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Token inválido ou não fornecido'
            ], 401);
        }

        return $next($request);
    }
}

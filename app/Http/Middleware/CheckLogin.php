<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Rutas que deben excluirse de la verificación de autenticación
        $excludedRoutes = [
            'login',
            '/'
        ];

        if (in_array($request->path(), $excludedRoutes)) {
            return $next($request);
        }

        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Debe iniciar sesión.'], 401);
            }

            return $next($request);
        } catch (UnauthorizedException $e) {
            return response()->json(['error' => 'Debe iniciar sesión.'], 403);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Debe iniciar sesión.'], 401);
            }

            if (!$request->user()->hasPermissionTo($permission)) {
                throw UnauthorizedException::forPermissions([$permission]);
            }

            return $next($request);
        } catch (UnauthorizedException $e) {
            return response()->json(['error' => 'No tiene permiso para acceder a esta ruta.'], 403);
        }
    }
}

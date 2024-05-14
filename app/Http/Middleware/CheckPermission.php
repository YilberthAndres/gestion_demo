<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        try {
            // Verifica si el usuario tiene el permiso necesario
            if (!$request->user()->hasPermissionTo($permission)) {
                // Si no tiene permiso, lanza una excepción UnauthorizedException
                throw UnauthorizedException::forPermissions([$permission]);
            }

            return $next($request);
        } catch (UnauthorizedException $e) {
            // Captura la excepción y devuelve una respuesta JSON
            throw new \App\Exceptions\UnauthorizedPermissionException();
        }
    }
}

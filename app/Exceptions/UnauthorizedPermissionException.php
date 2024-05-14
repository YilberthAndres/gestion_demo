<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedPermissionException extends Exception
{
    public function render($request)
    {
        return response()->json(['error' => 'No tienes permiso para realizar esta acción.'], 403);
    }
}

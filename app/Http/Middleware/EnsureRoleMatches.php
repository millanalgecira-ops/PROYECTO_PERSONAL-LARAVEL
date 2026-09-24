<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifica que el usuario autenticado en el guard "web" (staff) tenga uno
 * de los roles indicados. Uso: ->middleware('role:administrador')
 * o ->middleware('role:administrador,cocina').
 */
class EnsureRoleMatches
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = Auth::guard('web')->user();

        if (! $usuario || ! in_array($usuario->rolNombre(), $roles, true)) {
            abort(403, 'No tienes permiso para acceder a esta seccion.');
        }

        return $next($request);
    }
}

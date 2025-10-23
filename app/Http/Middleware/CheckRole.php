<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Middleware de rol.
     * Uso en rutas: ->middleware(['auth','role:alcalde']) o ->middleware(['auth','role:alcalde,ordenador_gasto'])
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Requiere que 'auth' se ejecute antes
        $user = $request->user();
        if (!$user) {
            abort(401, 'No autenticado.'); // 401 si no pasó por auth
        }

        // Cargar relación si falta
        $user->loadMissing('role');

        // Normalizar roles recibidos (limpiar espacios)
        $roles = array_map(static fn($r) => is_string($r) ? trim($r) : $r, $roles);

        // Permitir si el usuario tiene cualquiera de los roles indicados
        if ($user->role && in_array($user->role->name, $roles, true)) {
            return $next($request);
        }

        abort(403, 'No tienes el rol necesario para acceder a esta página.');
    }
}
    
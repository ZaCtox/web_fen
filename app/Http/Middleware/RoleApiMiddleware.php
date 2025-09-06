<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleApiMiddleware
{
    /**
     * Maneja la solicitud entrante (versión API).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user(); // Con Sanctum funciona bien

        if (!$user || !in_array($user->rol, $roles)) {
            return response()->json([
                'message' => 'Acceso no autorizado.'
            ], 403);
        }

        return $next($request);
    }
}

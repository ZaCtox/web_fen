<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IncidentModificationMiddleware
{
    /**
     * Handle an incoming request.
     * Verifica si el usuario tiene permisos para modificar incidencias.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Roles que pueden modificar el estado de las incidencias
        $rolesPermitidos = ['administrador', 'director_administrativo', 'técnico', 'auxiliar', 'asistente_postgrado'];
        
        if (!in_array($user->rol, $rolesPermitidos)) {
            return redirect()->back()->with('error', 'No tienes permisos para cambiar el estado de las incidencias.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Actualizar última conexión si el usuario está autenticado
        if (auth()->check()) {
            try {
                auth()->user()->update(['last_login_at' => now()]);
                \Log::info('Last login updated for user: ' . auth()->user()->id);
            } catch (\Exception $e) {
                \Log::error('Error updating last login: ' . $e->getMessage());
            }
        }
        
        return $response;
    }
}

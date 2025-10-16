<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageView;
use Illuminate\Support\Facades\Auth;

class TrackPageViews
{
    /**
     * Páginas a trackear con su tipo
     */
    protected $trackedPages = [
        // Páginas públicas (sin login)
        'public.dashboard.index' => 'inicio_publico',
        'public.calendario.index' => 'calendario_publico',
        'public.Equipo-FEN.index' => 'equipo_publico',
        'public.rooms.index' => 'salas_publico',
        'public.courses.index' => 'cursos_publico',
        'public.informes.index' => 'archivos_publico',
        
        // Páginas con sesión (autenticadas)
        'dashboard' => 'dashboard_autenticado',
        'calendario' => 'calendario_autenticado',
        'incidencias.index' => 'incidencias_autenticado',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo trackear peticiones GET exitosas
        if ($request->isMethod('GET') && $response->isSuccessful()) {
            $this->trackPageView($request);
        }

        return $response;
    }

    /**
     * Registra la visita a la página
     */
    protected function trackPageView(Request $request): void
    {
        try {
            $routeName = $request->route()?->getName();
            
            // Solo trackear si la ruta está en nuestra lista
            if (!$routeName || !isset($this->trackedPages[$routeName])) {
                return;
            }

            $pageType = $this->trackedPages[$routeName];

            PageView::create([
                'user_id' => Auth::id(), // null si es visitante anónimo
                'page_type' => $pageType,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => session()->getId(),
                'visited_at' => now(),
            ]);
        } catch (\Exception $e) {
            // No interrumpir la petición si falla el tracking
            \Log::error('Error tracking page view: ' . $e->getMessage());
        }
    }
}

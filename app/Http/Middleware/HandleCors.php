<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleCors
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $allowedOrigins = [
            'http://localhost:3000',
            'http://127.0.0.1:8000',
            'https://web-fen.up.railway.app',
        ];

        $origin = $request->headers->get('origin');

        if (in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Authorization, X-CSRF-TOKEN');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        // Responder preflight OPTIONS
        if ($request->getMethod() === "OPTIONS") {
            return response()->json([], 204, $response->headers->all());
        }

        return $response;
    }
}

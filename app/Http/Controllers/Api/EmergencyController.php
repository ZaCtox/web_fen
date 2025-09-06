<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emergency;

class EmergencyController extends Controller
{
    // Obtener la emergencia activa
    public function active()
    {
        $emergency = Emergency::where('active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        return response()->json($emergency);
    }

    // Listar todas las emergencias (por si las quieres mostrar en historial)
    public function index()
    {
        return response()->json(
            Emergency::orderBy('created_at', 'desc')->get()
        );
    }
}

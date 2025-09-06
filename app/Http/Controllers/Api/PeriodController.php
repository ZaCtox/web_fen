<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    // Devuelve todos los períodos ordenados
    public function index()
    {
        $periods = Period::orderByDesc('anio')->orderBy('numero')->get();
        return response()->json($periods);
    }

    // Devuelve un período específico por ID
    public function show($id)
    {
        $period = Period::find($id);

        if (!$period) {
            return response()->json(['error' => 'Período no encontrado'], 404);
        }

        return response()->json($period);
    }

    // Devuelve el período actual según una fecha enviada
    public function periodoPorFecha(Request $request)
    {
        $fecha = $request->query('fecha');
        if (!$fecha) {
            return response()->json(['error' => 'Se requiere parámetro fecha'], 400);
        }

        $periodo = Period::whereDate('fecha_inicio', '<=', $fecha)
            ->whereDate('fecha_fin', '>=', $fecha)
            ->first();

        return response()->json($periodo ?? null);
    }
}

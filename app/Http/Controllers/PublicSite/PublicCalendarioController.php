<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;


class PublicCalendarioController extends Controller
{
    public function index(Request $request)
    {
        // Obtener cohortes disponibles
        $cohortes = Period::select('cohorte')
            ->distinct()
            ->whereNotNull('cohorte')
            ->orderBy('cohorte', 'desc')
            ->pluck('cohorte');

        // Cohorte seleccionada (por defecto la más reciente)
        $cohorteSeleccionada = $request->get('cohorte', $cohortes->first());

        $periodoActual = Period::orderByDesc('anio')->orderByDesc('numero')->first();
        $fechaInicio = optional($periodoActual)->fecha_inicio?->format('Y-m-d') ?? now()->format('Y-m-d');
        
        // Obtener períodos de la cohorte seleccionada
        $periodos = Period::where('cohorte', $cohorteSeleccionada)
            ->orderBy('anio')
            ->orderBy('numero')
            ->get();

        return view('public.calendario', compact('fechaInicio', 'cohortes', 'cohorteSeleccionada', 'periodos'));
    }
}








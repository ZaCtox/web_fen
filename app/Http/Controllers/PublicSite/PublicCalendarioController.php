<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;


class PublicCalendarioController extends Controller
{
    public function index(Request $request)
    {
        // Obtener años de ingreso disponibles
        $aniosIngreso = Period::select('anio_ingreso')
            ->distinct()
            ->whereNotNull('anio_ingreso')
            ->orderBy('anio_ingreso', 'desc')
            ->pluck('anio_ingreso');

        // Año de ingreso seleccionado (por defecto el más reciente)
        $anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());

        // Obtener el primer período del año de ingreso seleccionado
        $primerPeriodo = Period::where('anio_ingreso', $anioIngresoSeleccionado)
            ->orderBy('anio')
            ->orderBy('numero')
            ->first();
        
        $fechaInicio = optional($primerPeriodo)->fecha_inicio?->format('Y-m-d') ?? now()->format('Y-m-d');
        
        // Obtener períodos del año de ingreso seleccionado
        $periodos = Period::where('anio_ingreso', $anioIngresoSeleccionado)
            ->orderBy('anio')
            ->orderBy('numero')
            ->get();

        return view('public.calendario', compact('fechaInicio', 'aniosIngreso', 'anioIngresoSeleccionado', 'periodos'));
    }
}









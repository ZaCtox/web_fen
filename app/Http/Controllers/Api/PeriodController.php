<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PeriodController extends Controller
{
    // Listar todos los períodos
    public function index()
    {
        $periods = Period::orderByDesc('anio')->orderBy('numero')->get();
        return response()->json($periods);
    }

    // Mostrar un período
    public function show($id)
    {
        $period = Period::find($id);

        if (!$period) {
            return response()->json(['message' => 'Periodo no encontrado'], 404);
        }

        return response()->json($period);
    }

    // Crear período
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anio' => 'required|integer|min:1|max:10',
            'numero' => 'required|integer|between:1,6',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio'
        ]);

        $period = Period::create($validated);

        return response()->json([
            'message' => 'Periodo creado correctamente.',
            'data' => $period
        ], 201);
    }

    // Actualizar período
    public function update(Request $request, $id)
    {
        $period = Period::find($id);

        if (!$period) {
            return response()->json(['message' => 'Periodo no encontrado'], 404);
        }

        $validated = $request->validate([
            'anio' => 'required|integer|min:1|max:10',
            'numero' => 'required|integer|between:1,6',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio'
        ]);

        $period->update($validated);

        return response()->json([
            'message' => 'Periodo actualizado correctamente.',
            'data' => $period
        ]);
    }

    // Eliminar período
    public function destroy($id)
    {
        $period = Period::find($id);

        if (!$period) {
            return response()->json(['message' => 'Periodo no encontrado'], 404);
        }

        $period->delete();

        return response()->json(['message' => 'Periodo eliminado correctamente.']);
    }

    // Avanzar fechas al próximo año
    public function actualizarAlProximoAnio()
    {
        $periodos = Period::all();

        if ($periodos->isEmpty()) {
            return response()->json(['message' => 'No hay períodos académicos para actualizar.'], 400);
        }

        foreach ($periodos as $periodo) {
            $periodo->update([
                'fecha_inicio' => Carbon::parse($periodo->fecha_inicio)->addYear(),
                'fecha_fin' => Carbon::parse($periodo->fecha_fin)->addYear(),
            ]);
        }

        return response()->json(['message' => 'Fechas actualizadas al próximo año.']);
    }

    // Trimestre siguiente según fecha
    public function trimestreSiguiente(Request $request)
    {
        $fechaActual = Carbon::parse($request->fecha);
        $actual = Period::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->first();

        if (!$actual) {
            return response()->json(['error' => 'No se encontró período actual'], 404);
        }

        $siguiente = Period::where('fecha_inicio', '>', $actual->fecha_inicio)
            ->orderBy('fecha_inicio')
            ->first();

        return $siguiente
            ? response()->json(['fecha_inicio' => $siguiente->fecha_inicio->toDateString()])
            : response()->json(['error' => 'No hay trimestre siguiente'], 404);
    }

    // Trimestre anterior según fecha
    public function trimestreAnterior(Request $request)
    {
        $fechaActual = Carbon::parse($request->fecha);
        $actual = Period::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->first();

        if (!$actual) {
            return response()->json(['error' => 'No se encontró período actual'], 404);
        }

        $anterior = Period::where('fecha_inicio', '<', $actual->fecha_inicio)
            ->orderByDesc('fecha_inicio')
            ->first();

        return $anterior
            ? response()->json(['fecha_inicio' => $anterior->fecha_inicio->toDateString()])
            : response()->json(['error' => 'No hay trimestre anterior'], 404);
    }

    // Buscar período que contenga una fecha
    public function periodoPorFecha(Request $request)
    {
        $fecha = Carbon::parse($request->query('fecha'))->startOfDay();

        $periodo = Period::whereDate('fecha_inicio', '<=', $fecha)
            ->whereDate('fecha_fin', '>=', $fecha)
            ->first();

        return response()->json([
            'periodo' => $periodo ? [
                'id' => $periodo->id,
                'anio' => $periodo->anio,
                'numero' => $periodo->numero,
            ] : null
        ]);
    }
}

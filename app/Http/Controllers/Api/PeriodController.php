<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PeriodController extends Controller
{
    // Listar todos los períodos
    public function index(Request $request)
    {
        $query = Period::query();

        // Filtros opcionales
        if ($request->filled('magister_id')) {
            $query->where('magister_id', $request->magister_id);
        }

        if ($request->filled('anio')) {
            $query->where('anio', $request->anio);
        }

        if ($request->filled('anio_ingreso')) {
            $query->where('anio_ingreso', $request->anio_ingreso);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('anio', 'like', '%' . $request->search . '%')
                  ->orWhere('numero', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->get('per_page', 15);
        $periods = $query->orderByDesc('anio')->orderBy('numero')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $periods,
            'message' => 'Períodos obtenidos exitosamente'
        ]);
    }

    // Mostrar un período
    public function show($id)
    {
        $period = Period::find($id);

        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Período no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $period,
            'message' => 'Período obtenido exitosamente'
        ]);
    }

    // Crear período
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anio' => 'required|integer|min:1|max:10',
            'numero' => 'required|integer|between:1,6',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'anio_ingreso' => 'nullable|integer|min:2020|max:2030'
        ]);

        $period = Period::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Período creado correctamente.',
            'data' => $period
        ], 201);
    }

    // Actualizar período
    public function update(Request $request, $id)
    {
        $period = Period::find($id);

        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Período no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'anio' => 'required|integer|min:1|max:10',
            'numero' => 'required|integer|between:1,6',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'anio_ingreso' => 'nullable|integer|min:2020|max:2030'
        ]);

        $period->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Período actualizado correctamente.',
            'data' => $period
        ]);
    }

    // Eliminar período
    public function destroy($id)
    {
        $period = Period::find($id);

        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Período no encontrado'
            ], 404);
        }

        $period->delete();

        return response()->json([
            'success' => true,
            'message' => 'Período eliminado correctamente.'
        ]);
    }

    // Avanzar fechas al próximo año
    public function actualizarAlProximoAnio()
    {
        $periodos = Period::all();

        if ($periodos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay períodos académicos para actualizar.'
            ], 400);
        }

        foreach ($periodos as $periodo) {
            $periodo->update([
                'fecha_inicio' => Carbon::parse($periodo->fecha_inicio)->addYear(),
                'fecha_fin' => Carbon::parse($periodo->fecha_fin)->addYear(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fechas actualizadas al próximo año correctamente.',
            'data' => $periodos
        ]);
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

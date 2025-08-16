<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PeriodController extends Controller
{
    public function index()
    {
        $this->authorizeAccess();

        $periods = Period::orderByDesc('anio')->orderBy('numero')->get();
        return view('periods.index', compact('periods'));
    }

    public function create()
    {
        $this->authorizeAccess();

        return view('periods.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();

        $request->validate([
            'anio' => 'required|integer|min:1|max:10',
            'numero' => 'required|integer|between:1,6',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio'
        ]);

        Period::create($request->only('anio', 'numero', 'fecha_inicio', 'fecha_fin'));

        return redirect()->route('periods.index')->with('success', 'Periodo creado correctamente.');
    }

    public function edit(Period $period)
    {
        $this->authorizeAccess();

        return view('periods.edit', ['period' => $period]);
    }

    public function update(Request $request, Period $period)
    {
        $this->authorizeAccess();

        $request->validate([
            'anio' => 'required|integer|min:1|max:10',
            'numero' => 'required|integer|between:1,6',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio'
        ]);

        $period->update($request->only('anio', 'numero', 'fecha_inicio', 'fecha_fin'));

        return redirect()->route('periods.index')->with('success', 'Periodo actualizado correctamente.');
    }

    public function destroy(Period $period)
    {
        $this->authorizeAccess();

        $period->delete();

        return redirect()->route('periods.index')->with('success', 'Periodo eliminado.');
    }

    public function actualizarAlProximoAnio()
    {
        $this->authorizeAccess();

        $periodos = Period::all();

        if ($periodos->isEmpty()) {
            return back()->with('error', 'No hay períodos académicos para actualizar.');
        }

        foreach ($periodos as $periodo) {
            $periodo->update([
                'fecha_inicio' => Carbon::parse($periodo->fecha_inicio)->addYear(),
                'fecha_fin' => Carbon::parse($periodo->fecha_fin)->addYear(),
            ]);
        }

        return back()->with('success', 'Fechas de todos los períodos se han actualizado al próximo año.');
    }

    public function trimestreSiguiente(Request $request)
    {
        $fechaActual = Carbon::parse($request->fecha);
        $actual = Period::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->first();

        if (!$actual)
            return response()->json(['error' => 'No se encontró período actual'], 404);

        $siguiente = Period::where('fecha_inicio', '>', $actual->fecha_inicio)
            ->orderBy('fecha_inicio')
            ->first();

        return $siguiente ? response()->json(['fecha_inicio' => $siguiente->fecha_inicio->toDateString()]) : response()->json(['error' => 'No hay trimestre siguiente'], 404);
    }

    public function trimestreAnterior(Request $request)
    {
        $fechaActual = Carbon::parse($request->fecha);
        $actual = Period::where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->first();

        if (!$actual)
            return response()->json(['error' => 'No se encontró período actual'], 404);

        $anterior = Period::where('fecha_inicio', '<', $actual->fecha_inicio)
            ->orderByDesc('fecha_inicio')
            ->first();

        return $anterior ? response()->json(['fecha_inicio' => $anterior->fecha_inicio->toDateString()]) : response()->json(['error' => 'No hay trimestre anterior'], 404);
    }

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

    private function authorizeAccess()
    {
        if (!tieneRol(['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }
}

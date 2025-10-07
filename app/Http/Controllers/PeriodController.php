<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Http\Requests\PeriodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PeriodController extends Controller
{
    public function index()
    {
        

        $periods = Period::orderByDesc('anio')->orderBy('numero')->get();
        return view('periods.index', compact('periods'));
    }

    public function create()
    {
        

        return view('periods.create');
    }

    public function store(PeriodRequest $request)
    {
        $data = $request->validated();
        $data['activo'] = true; // Siempre activo al crear
        
        Period::create($data);

        return redirect()->route('periods.index')->with('success', 'Período creado correctamente.');
    }

    public function edit(Period $period)
    {
        

        return view('periods.edit', ['period' => $period]);
    }

    public function update(PeriodRequest $request, Period $period)
    {
        $data = $request->validated();
        $data['activo'] = true; // Siempre activo al actualizar
        
        $period->update($data);

        return redirect()->route('periods.index')->with('success', 'Período actualizado correctamente.');
    }

    public function destroy(Period $period)
    {
        

        $period->delete();

        return redirect()->route('periods.index')->with('success', 'Periodo eliminado.');
    }

    public function actualizarAlProximoAnio()
    {
        

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

}

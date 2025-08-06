<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $periods = Period::orderByDesc('anio')->orderBy('numero')->get();
        return view('periods.index', compact('periods'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('periods.create');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $request->validate([
            'anio' => 'required|integer|min:1|max:10',
            'numero' => 'required|integer|between:1,6',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio'
        ]);

        Period::create([
            'anio' => $request->anio,
            'numero' => $request->numero,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin
        ]);

        return redirect()->route('periods.index')->with('success', 'Periodo creado correctamente.');
    }

    public function edit(Period $period)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('periods.edit', ['period' => $period]);
    }

    public function update(Request $request, Period $period)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $request->validate([
            'anio' => 'required|integer|min:1|max:10',
            'numero' => 'required|integer|between:1,6',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio'
        ]);

        $period->update([
            'anio' => $request->anio,
            'numero' => $request->numero,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin
        ]);

        return redirect()->route('periods.index')->with('success', 'Periodo actualizado correctamente.');
    }

    public function destroy(Period $period)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $period->delete();

        return redirect()->route('periods.index')->with('success', 'Periodo eliminado.');
    }
}

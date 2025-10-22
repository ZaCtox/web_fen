<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Http\Requests\PeriodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PeriodController extends Controller
{
    public function index(Request $request)
    {
        // Obtener magisters disponibles
        $magisters = \App\Models\Magister::orderBy('orden')->get();
        
        // Magister seleccionado (por defecto el primero)
        $magisterSeleccionado = $request->get('magister_id', $magisters->first()?->id);

        // Obtener años de ingreso disponibles para el magister seleccionado
        $aniosIngreso = Period::select('anio_ingreso')
            ->distinct()
            ->where('magister_id', $magisterSeleccionado)
            ->whereNotNull('anio_ingreso')
            ->orderBy('anio_ingreso', 'desc')
            ->pluck('anio_ingreso');

        // Año de ingreso seleccionado (por defecto el más reciente)
        $anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());

        // Filtrar períodos por magister y año de ingreso
        $query = Period::query();
        if ($magisterSeleccionado) {
            $query->where('magister_id', $magisterSeleccionado);
        }
        if ($anioIngresoSeleccionado) {
            $query->where('anio_ingreso', $anioIngresoSeleccionado);
        }

        $periods = $query->orderByDesc('anio')->orderBy('numero')->get();
        
        return view('periods.index', compact('periods', 'magisters', 'magisterSeleccionado', 'aniosIngreso', 'anioIngresoSeleccionado'));
    }

    public function create(Request $request)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para crear períodos.');
        }
        
        $anioIngreso = $request->get('anio_ingreso', null);
        $magisterId = $request->get('magister_id', null);
        $magisters = \App\Models\Magister::orderBy('orden')->get();
        
        return view('periods.create', compact('anioIngreso', 'magisterId', 'magisters'));
    }

    public function store(PeriodRequest $request)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para crear períodos.');
        }
        
        $data = $request->validated();
        $data['activo'] = true; // Siempre activo al crear
        
        Period::create($data);

        return redirect()->route('periods.index')->with('success', 'Período creado correctamente.');
    }

    public function edit(Period $period)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para editar períodos.');
        }
        
        $magisters = \App\Models\Magister::orderBy('orden')->get();
        
        return view('periods.edit', [
            'period' => $period,
            'magisters' => $magisters
        ]);
    }

    public function update(PeriodRequest $request, Period $period)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para actualizar períodos.');
        }
        
        $data = $request->validated();
        $data['activo'] = true; // Siempre activo al actualizar
        
        $period->update($data);

        return redirect()->route('periods.index')->with('success', 'Período actualizado correctamente.');
    }

    public function destroy(Period $period)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para eliminar períodos.');
        }
        
        $period->delete();

        return redirect()->route('periods.index')->with('success', 'Periodo eliminado.');
    }

    public function actualizarAlProximoAnio()
    {
        // Obtener todos los magisters
        $magisters = \App\Models\Magister::orderBy('orden')->get();
        
        // Redirigir al formulario de crear período con el año de ingreso pre-seleccionado
        return redirect()->route('periods.create', ['anio_ingreso' => now()->year + 1])
            ->with('success', "Selecciona el programa y crea el primer período para el año de ingreso " . (now()->year + 1) . ".");
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










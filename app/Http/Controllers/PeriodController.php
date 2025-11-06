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
        $anioIngreso = $request->get('anio_ingreso', null);
        $magisterId = $request->get('magister_id', null);
        $magisters = \App\Models\Magister::orderBy('orden')->get();
        
        // Obtener años de ingreso existentes en la BD
        $aniosIngresoExistentes = Period::select('anio_ingreso')
            ->distinct()
            ->whereNotNull('anio_ingreso')
            ->orderBy('anio_ingreso', 'desc')
            ->pluck('anio_ingreso');
        
        return view('periods.create', compact('anioIngreso', 'magisterId', 'magisters', 'aniosIngresoExistentes'));
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
        $magisters = \App\Models\Magister::orderBy('orden')->get();
        
        // Obtener años de ingreso existentes en la BD
        $aniosIngresoExistentes = Period::select('anio_ingreso')
            ->distinct()
            ->whereNotNull('anio_ingreso')
            ->orderBy('anio_ingreso', 'desc')
            ->pluck('anio_ingreso');
        
        return view('periods.edit', [
            'period' => $period,
            'magisters' => $magisters,
            'aniosIngresoExistentes' => $aniosIngresoExistentes
        ]);
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

    public function actualizarAlProximoAnio(Request $request)
    {
        try {
            // Obtener año de ingreso del request o usar el siguiente año
            $nuevoAnioIngreso = $request->get('anio_ingreso') ?: (now()->year + 1);
            
            // Validar año de ingreso
            if ($nuevoAnioIngreso < 2020 || $nuevoAnioIngreso > 2035) {
                return redirect()->route('periods.index')
                    ->with('error', 'El año de ingreso debe estar entre 2020 y 2035.');
            }
            
            // Obtener el magister seleccionado o el primero disponible
            $magisterId = $request->get('magister_id');
            if (!$magisterId) {
                $primerMagister = \App\Models\Magister::orderBy('orden')->first();
                if (!$primerMagister) {
                    return redirect()->route('periods.index')
                        ->with('error', 'No hay programas de magíster registrados. Primero crea un programa.');
                }
                $magisterId = $primerMagister->id;
            }
            
            // Verificar si ya existe el primer período para este año de ingreso y magister
            $periodoExistente = Period::where('magister_id', $magisterId)
                ->where('anio_ingreso', $nuevoAnioIngreso)
                ->where('anio', 1)
                ->where('numero', 1)
                ->first();
            
            if ($periodoExistente) {
                return redirect()->route('periods.index', ['magister_id' => $magisterId, 'anio_ingreso' => $nuevoAnioIngreso])
                    ->with('info', "El Año 1 - Trimestre 1 ya existe para el año de ingreso $nuevoAnioIngreso. Ahora puedes crear los siguientes trimestres.");
            }
            
            // Obtener fechas del request o usar fechas por defecto
            $fechaInicio = $request->get('fecha_inicio');
            $fechaFin = $request->get('fecha_fin');
            
            // Validar que las fechas vengan del request
            if (!$fechaInicio || !$fechaFin) {
                return redirect()->route('periods.index')
                    ->with('error', 'Las fechas de inicio y término son requeridas.');
            }
            
            // Validar que fecha_fin sea posterior a fecha_inicio
            $fechaInicioCarbon = Carbon::parse($fechaInicio);
            $fechaFinCarbon = Carbon::parse($fechaFin);
            
            if ($fechaFinCarbon->lte($fechaInicioCarbon)) {
                return redirect()->route('periods.index')
                    ->with('error', 'La fecha de término debe ser posterior a la fecha de inicio.');
            }
            
            // Crear el primer período automáticamente
            Period::create([
                'magister_id' => $magisterId,
                'anio' => 1,
                'numero' => 1,
                'anio_ingreso' => $nuevoAnioIngreso,
                'fecha_inicio' => $fechaInicioCarbon,
                'fecha_fin' => $fechaFinCarbon,
                'activo' => true
            ]);
            
            return redirect()->route('periods.index', ['magister_id' => $magisterId, 'anio_ingreso' => $nuevoAnioIngreso])
                ->with('success', "Año 1 - Trimestre 1 creado para el año de ingreso $nuevoAnioIngreso. Ahora puedes crear los siguientes trimestres.");
                
        } catch (\Exception $e) {
            \Log::error('Error al crear año de ingreso: ' . $e->getMessage());
            return redirect()->route('periods.index')
                ->with('error', 'Error al crear el año de ingreso. ' . $e->getMessage());
        }
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











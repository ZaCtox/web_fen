<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaseRequest;
use App\Http\Requests\UpdateClaseRequest;
use App\Models\Clase;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Period;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClaseController extends Controller
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

        $query = Clase::with(['course.magister', 'period', 'room', 'sesiones']);

        // Filtros de periodo (anio_ingreso, año, trimestre) - todos juntos en una sola consulta
        $query->whereHas('period', function($q) use ($anioIngresoSeleccionado, $request) {
            if ($anioIngresoSeleccionado) {
                $q->where('anio_ingreso', $anioIngresoSeleccionado);
            }
            if ($request->filled('anio')) {
                $q->where('anio', $request->anio);
            }
            if ($request->filled('trimestre')) {
                $q->where('numero', $request->trimestre);
            }
        });

        if ($request->filled('magister')) {
            $query->whereHas('course.magister', fn($q) => $q->where('nombre', $request->magister));
        }

        if ($request->filled('room_id')) {
            $query->whereHas('room', fn($q) => $q->where('name', $request->room_id));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $clases = $query
            ->with('sesiones') // Eager load sesiones para ordenar
            ->orderBy('period_id')
            ->orderBy('course_id')
            ->paginate(12)
            ->appends($request->query());

        $anios = Period::where('anio_ingreso', $anioIngresoSeleccionado)->distinct()->orderByDesc('anio')->pluck('anio');
        $periodos = Period::where('anio_ingreso', $anioIngresoSeleccionado)->orderByDesc('anio')->orderBy('numero')->get();

        return view('clases.index', [
            'clases'    => $clases,
            'rooms'     => Room::orderBy('name')->get(),
            'magisters' => Magister::orderBy('orden')->get(),
            'anios'     => $anios,
            'periodos'  => $periodos,
            'aniosIngreso'  => $aniosIngreso,
            'anioIngresoSeleccionado' => $anioIngresoSeleccionado,
        ]);
    }

    public function create()
    {
        
        [$agrupados, $courses, $rooms, $periods] = $this->referencias();

        return view('clases.create', [
            'agrupados' => $agrupados,
            'rooms' => $rooms,
            'periods' => $periods,
        ]);
    }

    public function store(StoreClaseRequest $request)
    {
        
        $validated = $request->validated();
        
        // Crear la clase (módulo)
        $clase = Clase::create([
            'course_id' => $validated['course_id'],
            'period_id' => $validated['period_id'],
            'room_id' => $validated['room_id'] ?? null,
            'url_zoom' => $validated['url_zoom'] ?? null,
            'encargado' => $validated['encargado'],
        ]);

        // Crear las sesiones
        foreach ($validated['sesiones'] as $sesionData) {
            // Usar room_id de la sesión o el global si no está definido
            if (empty($sesionData['room_id'])) {
                $sesionData['room_id'] = $validated['room_id'] ?? null;
            }
            
            // Usar url_zoom de la sesión o el global si no está definido
            if (empty($sesionData['url_zoom'])) {
                $sesionData['url_zoom'] = $validated['url_zoom'] ?? null;
            }
            
            // Agregar estado por defecto
            $sesionData['estado'] = $sesionData['estado'] ?? 'pendiente';
            
            $clase->sesiones()->create($sesionData);
        }

        return redirect()->route('clases.show', $clase)->with('success', 'Clase y sesiones creadas correctamente.');
    }

    public function edit(Clase $clase)
    {
        
        [$agrupados, $courses, $rooms, $periods] = $this->referencias();

        return view('clases.edit', [
            'clase' => $clase,
            'agrupados' => $agrupados,
            'courses' => $courses,
            'rooms' => $rooms,
            'periods' => $periods,
        ]);
    }

    public function update(UpdateClaseRequest $request, Clase $clase)
    {
        
        $validated = $request->validated();
        
        // Actualizar solo los datos generales de la clase (módulo)
        $clase->update($validated);
        
        // Las sesiones se editan desde el show.blade.php individualmente
        return redirect()->route('clases.show', $clase)->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase)
    {
        
        $clase->delete();
        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }

    public function exportar(Request $request)
    {
        try {
            $filters = $request->only('magister', 'sala', 'anio_ingreso');
            
            // Consulta optimizada con límite para evitar sobrecarga
            $clases = Clase::with(['course.magister', 'period', 'room'])
                ->filtrar($filters)
                ->orderBy('period_id')
                ->orderBy('course_id')
                ->limit(1000) // Límite para evitar consultas muy pesadas
                ->get();

            if ($clases->isEmpty()) {
                return back()->with('warning', 'No se encontraron clases con los filtros aplicados.');
            }

            $nombreArchivo = 'clases_academicas_'.now()->format('Y-m-d_H-i').'.pdf';
            
            // Generar PDF de forma más eficiente
            $pdf = Pdf::loadView('clases.export', compact('clases', 'filters'))
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => false,
                    'dpi' => 150
                ]);

            return $pdf->download($nombreArchivo);
            
        } catch (\Exception $e) {
            \Log::error('Error generando PDF: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el PDF. Intenta nuevamente.');
        }
    }

    public function show(Clase $clase)
    {
        $clase->load(['course.magister', 'period', 'room', 'sesiones']);
        return view('clases.show', compact('clase'));
    }

    // Disponibilidad de sala para una combinación periodo/día/horario
    public function disponibilidad(Request $request)
    {
        $periodId = $request->query('period_id');
        $roomId = $request->query('room_id');
        $dia = $request->query('dia');
        $horaInicio = $request->query('hora_inicio');
        $horaFin = $request->query('hora_fin');
        $modality = $request->query('modality');
        $excludeSesionId = $request->query('exclude_sesion_id');

        // Si es online, siempre disponible
        if ($modality === 'online') {
            return response()->json(['available' => true, 'conflicts' => []]);
        }

        if (!$dia || !$horaInicio || !$horaFin || !$roomId) {
            return response()->json(['available' => true, 'conflicts' => []]);
        }

        // Buscar conflictos en ClaseSesion
        $conflicts = \App\Models\ClaseSesion::with(['clase.course.magister', 'room'])
            ->where('room_id', $roomId)
            ->where('dia', $dia)
            ->when($excludeSesionId, fn ($q) => $q->where('id', '!=', $excludeSesionId))
            ->where(function ($q) use ($horaInicio, $horaFin) {
                // Solapamiento de rangos de tiempo: (A.inicio < B.fin) AND (A.fin > B.inicio)
                $q->where('hora_inicio', '<', $horaFin)
                  ->where('hora_fin', '>', $horaInicio);
            })
            ->get()
            ->map(function ($sesion) {
                $clase = $sesion->clase;
                return [
                    'id' => $sesion->id,
                    'clase_id' => $clase->id,
                    'programa' => optional($clase->course->magister)->nombre ?? 'Sin programa',
                    'course_nombre' => $clase->course->nombre ?? 'Sin asignatura',
                    'encargado' => $clase->encargado ?? 'Sin encargado',
                    'dia' => $sesion->dia,
                    'fecha' => $sesion->fecha,
                    'hora_inicio' => substr($sesion->hora_inicio, 0, 5), // HH:MM
                    'hora_fin' => substr($sesion->hora_fin, 0, 5), // HH:MM
                    'modalidad' => $sesion->modalidad,
                    'sala' => $sesion->room->name ?? 'Sin sala',
                ];
            })
            ->values();

        return response()->json([
            'available' => $conflicts->isEmpty(),
            'conflicts' => $conflicts,
        ]);
    }

    // Generar slots disponibles dentro de un rango configurado
    public function horariosDisponibles(Request $request)
    {
        $periodId = $request->query('period_id');
        $roomId = $request->query('room_id');
        $dia = $request->query('dia');
        $modality = $request->query('modality');
        $excludeId = $request->query('exclude_id');
        $desde = $request->query('desde', '08:00');
        $hasta = $request->query('hasta', '22:00');
        $minBlock = max(60, (int) $request->query('min_block', 60));
        $buffer = 10; // fijo en bordes
        $blocks = max(1, min(5, (int) $request->query('blocks', 1)));

        // Si es online, no se requieren slots de sala
        if ($modality === 'online') {
            return response()->json(['slots' => [[
                'start' => $desde,
                'end' => $hasta,
            ]]]);
        }

        if (!$periodId || !$roomId || !$dia) {
            return response()->json(['slots' => []]);
        }

        // Traer sesiones existentes de ese día/sala
        $ocupadas = \App\Models\ClaseSesion::where('room_id', $roomId)
            ->where('dia', $dia)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('hora_inicio')
            ->get(['hora_inicio', 'hora_fin']);

        // Construir huecos a partir de [desde, hasta] descontando ocupadas + buffer
        $cursor = $desde;
        $slots = [];
        foreach ($ocupadas as $blk) {
            $finHueco = self::addMinutes($blk->hora_inicio, -$buffer);
            if (self::diffMinutes($cursor, $finHueco) >= $minBlock) {
                $slots[] = ['start' => $cursor, 'end' => $finHueco];
            }
            $cursor = self::addMinutes($blk->hora_fin, $buffer);
        }
        if (self::diffMinutes($cursor, $hasta) >= $minBlock) {
            $slots[] = ['start' => $cursor, 'end' => $hasta];
        }

        // Si se piden N bloques, aseguramos sumar 10 min entre bloques internos al sugerir chips en el cliente.
        // Aquí devolvemos los huecos (contiguos) y el cliente arma chips de 60*blocks respetando buffer externo.
        return response()->json(['slots' => $slots, 'blocks' => $blocks, 'block_minutes' => 60, 'edge_buffer' => $buffer]);
    }

    private static function addMinutes(string $hhmm, int $mins): string
    {
        [$H, $M] = array_map('intval', explode(':', $hhmm));
        $d = mktime($H, $M + $mins, 0, 1, 1, 2000);
        return date('H:i', $d);
    }

    private static function diffMinutes(string $from, string $to): int
    {
        [$h1, $m1] = array_map('intval', explode(':', $from));
        [$h2, $m2] = array_map('intval', explode(':', $to));
        return ($h2 * 60 + $m2) - ($h1 * 60 + $m1);
    }

    // Sugerir salas alternativas disponibles
    public function salasDisponibles(Request $request)
    {
        $dia = $request->query('dia');
        $horaInicio = $request->query('hora_inicio');
        $horaFin = $request->query('hora_fin');
        $modalidad = $request->query('modalidad');

        if (!$dia || !$horaInicio || !$horaFin) {
            return response()->json(['salas' => []]);
        }

        // Si es online, no necesita sala
        if ($modalidad === 'online') {
            return response()->json(['salas' => []]);
        }

        // Obtener todas las salas
        $todasLasSalas = Room::orderBy('name')->get();

        // Obtener salas ocupadas en ese horario/día
        $salasOcupadas = \App\Models\ClaseSesion::where('dia', $dia)
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where('hora_inicio', '<', $horaFin)
                  ->where('hora_fin', '>', $horaInicio);
            })
            ->pluck('room_id')
            ->unique()
            ->toArray();

        // Filtrar salas disponibles
        $salasDisponibles = $todasLasSalas->filter(function ($sala) use ($salasOcupadas) {
            return !in_array($sala->id, $salasOcupadas);
        })->map(function ($sala) {
            return [
                'id' => $sala->id,
                'name' => $sala->name,
                'location' => $sala->location ?? 'Sin ubicación',
                'capacity' => $sala->capacity ?? 0,
            ];
        })->values();

        return response()->json(['salas' => $salasDisponibles]);
    }

    private function referencias()
    {
        $courses = Course::with('magister', 'period')->get();
        
        // Convertir cursos a arrays con las propiedades que espera el JavaScript
        $agrupados = $courses->groupBy(fn ($c) => $c->magister->nombre ?? 'Sin Magíster')
            ->map(fn ($group) => $group->map(function ($curso) {
                return [
                    'id' => $curso->id,
                    'nombre' => $curso->nombre,
                    'period_id' => $curso->period_id,
                    'periodo' => $curso->period ? "{$curso->period->anio} - Trimestre {$curso->period->numero}" : '',
                    'numero' => $curso->period->numero ?? '',
                    'anio' => $curso->period->anio ?? '',
                ];
            })->values());

        return [$agrupados, $courses, Room::orderBy('name')->get(), Period::orderByDesc('anio')->orderBy('numero')->get()];
    }
}







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
        $query = Clase::with(['course.magister', 'period', 'room']);

        if ($request->filled('magister')) {
            $query->whereHas('course.magister', fn($q) => $q->where('nombre', $request->magister));
        }

        if ($request->filled('room_id')) {
            $query->whereHas('room', fn($q) => $q->where('name', $request->room_id));
        }

        if ($request->filled('dia')) {
            $query->where('dia', $request->dia);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('anio')) {
            $query->whereHas('period', fn($q) => $q->where('anio', $request->anio));
        }

        if ($request->filled('trimestre')) {
            $query->whereHas('period', fn($q) => $q->where('numero', $request->trimestre));
        }

        $clases = $query
            ->orderBy('period_id')
            ->orderByRaw("FIELD(dia, 'Viernes','Sábado')")
            ->orderBy('hora_inicio')
            ->paginate(12)
            ->appends($request->query());

        $anios = Period::distinct()->orderByDesc('anio')->pluck('anio');
        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();

        return view('clases.index', [
            'clases'    => $clases,
            'rooms'     => Room::orderBy('name')->get(),
            'magisters' => Magister::orderBy('nombre')->get(),
            'anios'     => $anios,
            'periodos'  => $periodos,
        ]);
    }

    public function create()
    {
        $courses = Course::with('magister', 'period')->get();
        $agrupados = [];
        foreach ($courses as $course) {
            $agrupados[$course->magister->nombre][] = [
                'id' => $course->id,
                'nombre' => $course->nombre,
                'period_id' => $course->period_id,
                'periodo' => $course->period?->nombre_completo ?? 'Sin período',
                'anio' => $course->period?->anio ?? null,
                'numero' => $course->period?->numero ?? null,
            ];
        }

        return view('clases.create', [
            'agrupados' => $agrupados,
            'rooms' => Room::orderBy('name')->get(),
            'periods' => Period::orderBy('anio', 'desc')->orderBy('numero')->get(),
            'anios' => Period::distinct()->orderByDesc('anio')->pluck('anio'),
            'trimestres' => Period::distinct()->orderBy('numero')->pluck('numero'),
            'tipos' => ['cátedra', 'taller', 'laboratorio', 'ayudantía'],
            'action' => route('clases.store'),
            'method' => 'POST',
            'submitText' => '💾 Crear Clase',
        ]);
    }

    public function store(StoreClaseRequest $request)
    {
        Clase::create($request->validated());
        return redirect()->route('clases.index')->with('success', 'Clase creada correctamente.');
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
            'anios' => Period::distinct()->orderByDesc('anio')->pluck('anio'),
            'trimestres' => Period::distinct()->orderBy('numero')->pluck('numero'),
            'tipos' => ['cátedra', 'taller', 'laboratorio', 'ayudantía'],
        ]);
    }

    public function update(UpdateClaseRequest $request, Clase $clase)
    {
        $clase->update($request->validated());
        return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase)
    {
        $clase->delete();
        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }

    public function exportar(Request $request)
    {
        $filters = $request->only('magister', 'sala', 'dia');
        $clases = Clase::with(['course.magister', 'period', 'room'])
            ->filtrar($filters)
            ->orderBy('period_id')
            ->orderByRaw("FIELD(dia, 'Viernes','Sábado')")
            ->orderBy('hora_inicio')
            ->get();

        if ($clases->isEmpty()) {
            return back()->with('warning', 'No se encontraron clases con los filtros aplicados.');
        }

        $nombreArchivo = 'clases_academicas_'.now()->format('Y-m-d_H-i').'.pdf';
        $pdf = Pdf::loadView('clases.export', compact('clases'))->setPaper('a4', 'landscape');

        return $pdf->download($nombreArchivo);
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
        $excludeId = $request->query('exclude_id');

        // Si es online, siempre disponible
        if ($modality === 'online') {
            return response()->json(['available' => true, 'conflicts' => []]);
        }

        if (!$periodId || !$dia || !$horaInicio || !$horaFin || !$roomId) {
            return response()->json(['available' => true, 'conflicts' => []]);
        }

        $conflicts = Clase::with(['course.magister', 'room'])
            ->where('period_id', $periodId)
            ->where('room_id', $roomId)
            ->where('dia', $dia)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($horaInicio, $horaFin) {
                // Solapamiento de rangos de tiempo
                $q->whereBetween('hora_inicio', [$horaInicio, $horaFin])
                  ->orWhereBetween('hora_fin', [$horaInicio, $horaFin])
                  ->orWhere(function ($qq) use ($horaInicio, $horaFin) {
                      $qq->where('hora_inicio', '<=', $horaInicio)
                         ->where('hora_fin', '>=', $horaFin);
                  });
            })
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'programa' => optional($c->course->magister)->nombre,
                    'curso' => $c->course->nombre ?? 'Curso',
                    'dia' => $c->dia,
                    'hora_inicio' => $c->hora_inicio,
                    'hora_fin' => $c->hora_fin,
                    'sala' => $c->room->name ?? '-',
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

        // Traer clases existentes de ese día/periodo/sala
        $ocupadas = Clase::where('period_id', $periodId)
            ->where('room_id', $roomId)
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

    private function referencias()
    {
        $courses = Course::with('magister', 'period')->get();
        $agrupados = $courses->groupBy(fn ($c) => $c->magister->nombre ?? 'Sin Magíster')
            ->map(fn ($group) => $group->map(fn ($c) => [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'period_id' => $c->period_id,
                'periodo' => $c->period?->nombre_completo ?? 'Sin periodo',
                'anio' => $c->period?->anio ?? null,
                'numero' => $c->period?->numero ?? null,
            ])->values());

        return [$agrupados, $courses, Room::orderBy('name')->get(), Period::orderByDesc('anio')->orderBy('numero')->get()];
    }
}

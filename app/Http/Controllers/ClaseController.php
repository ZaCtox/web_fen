<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Course;
use App\Models\Period;
use App\Models\Room;
use App\Models\Magister;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StoreClaseRequest;
use Carbon\Carbon;
use App\Http\Requests\UpdateClaseRequest;

class ClaseController extends Controller
{
    private function authorizeAccess()
    {
        if (!tieneRol(['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function index()
    {
        $this->authorizeAccess();

        $clases = Clase::with(['course.magister', 'period', 'room'])
            ->orderBy('period_id')
            ->orderByRaw("FIELD(dia, 'Viernes','Sábado')") // opcional
            ->orderBy('hora_inicio')
            ->get();

        // Listas para filtros por año y trimestre (si los usas en index)
        $anios = Period::distinct()->orderByDesc('anio')->pluck('anio');
        $trimestres = Period::distinct()->orderBy('numero')->pluck('numero');

        return view('clases.index', [
            'clases' => $clases,
            'rooms' => Room::orderBy('name')->get(),
            'magisters' => Magister::orderBy('nombre')->get(),
            'anios' => $anios,
            'trimestres' => $trimestres,
        ]);
    }

    public function create()
    {
        $this->authorizeAccess();

        $courses = Course::with('magister', 'period')->get();
        $agrupados = [];

        foreach ($courses as $course) {
            $agrupados[$course->magister->nombre][] = [
                'id' => $course->id,
                'nombre' => $course->nombre,
                'period_id' => $course->period_id,
                'periodo' => $course->period ? $course->period->nombre_completo : 'Sin período',
                // 👇 datos separados del período (por si quieres mostrarlos en el select)
                'anio' => $course->period->anio ?? null,
                'numero' => $course->period->numero ?? null,
            ];
        }

        $rooms = Room::orderBy('name')->get();
        $periods = Period::orderBy('anio', 'desc')->orderBy('numero')->get();
        $anios = Period::distinct()->orderByDesc('anio')->pluck('anio');
        $trimestres = Period::distinct()->orderBy('numero')->pluck('numero');

        // Opciones de tipo (según indicaste)
        $tipos = ['clase', 'taller', 'laboratorio', 'ayudantia'];

        return view('clases.create', [
            'agrupados' => $agrupados,
            'rooms' => $rooms,
            'periods' => $periods,
            'anios' => $anios,
            'trimestres' => $trimestres,
            'tipos' => $tipos,
            'action' => route('clases.store'),
            'method' => 'POST',
            'submitText' => '💾 Crear Clase',
        ]);
    }

    public function store(StoreClaseRequest $request)
    {
        $this->authorizeAccess();

        // Ya tienes validado y agregado en el modelo (según comentaste)
        Clase::create($request->validated());

        return redirect()->route('clases.index')->with('success', 'Clase creada correctamente.');
    }

    public function edit(Clase $clase)
    {
        $this->authorizeAccess();

        [$agrupados, $courses, $rooms, $periods] = $this->referencias();

        $anios = Period::distinct()->orderByDesc('anio')->pluck('anio');
        $trimestres = Period::distinct()->orderBy('numero')->pluck('numero');
        $tipos = ['clase', 'taller', 'laboratorio', 'ayudantia'];

        return view('clases.edit', [
            'clase' => $clase,
            'agrupados' => $agrupados,
            'courses' => $courses,
            'rooms' => $rooms,
            'periods' => $periods,
            'anios' => $anios,
            'trimestres' => $trimestres,
            'tipos' => $tipos,
        ]);
    }

    public function update(UpdateClaseRequest $request, Clase $clase)
    {
        $this->authorizeAccess();

        $clase->update($request->validated());

        return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase)
    {
        $this->authorizeAccess();

        $clase->delete();

        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }

    public function exportar(Request $request)
    {
        $this->authorizeAccess();

        $filters = $request->only('magister', 'sala', 'dia');

        $clases = Clase::with(['course.magister', 'period', 'room'])
            ->filtrar($filters)
            ->orderBy('period_id')
            ->orderByRaw("FIELD(dia, 'Viernes','Sábado')") // opcional
            ->orderBy('hora_inicio')
            ->get();

        if ($clases->isEmpty()) {
            return back()->with('warning', 'No se encontraron clases con los filtros aplicados.');
        }

        $nombreArchivo = 'clases_academicas_' . now()->format('Y-m-d_H-i') . '.pdf';

        $pdf = Pdf::loadView('clases.export', compact('clases'))->setPaper('a4', 'landscape');
        return $pdf->download($nombreArchivo);
    }

    public function show(Clase $clase)
    {
        $this->authorizeAccess();

        $clase->load(['course.magister', 'period', 'room']);

        return view('clases.show', compact('clase'));
    }

    private function referencias()
    {
        $courses = Course::with('magister', 'period')->get();

        $agrupados = $courses->groupBy(fn($c) => $c->magister->nombre ?? 'Sin Magíster')
            ->map(fn($group) => $group->map(fn($c) => [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'period_id' => $c->period_id,
                'periodo' => $c->period?->nombre_completo ?? 'Sin periodo',
                // 👇 también acá por consistencia
                'anio' => $c->period?->anio ?? null,
                'numero' => $c->period?->numero ?? null,
            ])->values());

        $rooms = Room::orderBy('name')->get();
        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();

        return [$agrupados, $courses, $rooms, $periodos];
    }
    public function disponibilidad(Request $request)
    {
        $this->authorizeAccess();

        $data = $request->validate([
            'room_id' => ['nullable', 'integer', 'exists:rooms,id'],
            'period_id' => ['required', 'integer', 'exists:periods,id'],
            'dia' => ['required', 'in:Viernes,Sábado'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'exclude_id' => ['nullable', 'integer', 'exists:clases,id'], // para editar
            'modality' => ['nullable', 'string'], // para ignorar si es online
        ]);

        // Si es online o sin sala => siempre disponible
        if (($data['modality'] ?? null) === 'online' || empty($data['room_id'])) {
            return response()->json(['available' => true, 'conflicts' => []]);
        }

        $conflicts = \App\Models\Clase::query()
            ->with(['course.magister', 'room'])
            ->where('room_id', $data['room_id'])
            ->where('period_id', $data['period_id'])
            ->where('dia', $data['dia'])
            // solapamiento de horas: (inicio < fin_existente) y (fin > inicio_existente)
            ->where(function ($q) use ($data) {
                $q->where('hora_inicio', '<', $data['hora_fin'])
                    ->where('hora_fin', '>', $data['hora_inicio']);
            })
            ->when(!empty($data['exclude_id']), fn($q) => $q->where('id', '!=', $data['exclude_id']))
            ->orderBy('hora_inicio')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'curso' => $c->course->nombre ?? '—',
                    'programa' => $c->course->magister->nombre ?? '—',
                    'sala' => $c->room->name ?? '—',
                    'dia' => $c->dia,
                    'hora_inicio' => $c->hora_inicio,
                    'hora_fin' => $c->hora_fin,
                ];
            })
            ->values();

        return response()->json([
            'available' => $conflicts->isEmpty(),
            'conflicts' => $conflicts,
        ]);
    }

    public function horariosDisponibles(Request $request)
    {
        $this->authorizeAccess();

        $data = $request->validate([
            'room_id' => ['nullable', 'integer', 'exists:rooms,id'],
            'period_id' => ['required', 'integer', 'exists:periods,id'],
            'dia' => ['required', 'in:Viernes,Sábado'],
            'modality' => ['nullable', 'string'],
            'exclude_id' => ['nullable', 'integer', 'exists:clases,id'],

            // ventana de búsqueda, por defecto 08:00–22:00
            'desde' => ['nullable', 'date_format:H:i'],
            'hasta' => ['nullable', 'date_format:H:i', 'after:desde'],

            // bloque mínimo en minutos (por defecto 60)
            'min_block' => ['nullable', 'integer', 'min:30'],
            // holgura en minutos (por defecto 10)
            'buffer' => ['nullable', 'integer', 'min:0', 'max:60'],
        ]);

        // Si es online o sin sala => todo disponible
        if (($data['modality'] ?? null) === 'online' || empty($data['room_id'])) {
            $desde = $data['desde'] ?? '08:00';
            $hasta = $data['hasta'] ?? '22:00';
            return response()->json([
                'available' => true,
                'slots' => [['start' => $desde, 'end' => $hasta]],
            ]);
        }

        $desde = $data['desde'] ?? '08:00';
        $hasta = $data['hasta'] ?? '22:00';
        $minBlock = (int) ($data['min_block'] ?? 60); // 1 hora
        $buffer = (int) ($data['buffer'] ?? 10); // 10 min

        // 1) Traer clases existentes de la sala para ese periodo y día
        $ocupadas = \App\Models\Clase::query()
            ->where('room_id', $data['room_id'])
            ->where('period_id', $data['period_id'])
            ->where('dia', $data['dia'])
            ->when(!empty($data['exclude_id']), fn($q) => $q->where('id', '!=', $data['exclude_id']))
            ->orderBy('hora_inicio')
            ->get(['hora_inicio', 'hora_fin'])
            // 2) Expandir cada bloque con buffer (inicio-10, fin+10)
            ->map(function ($c) use ($buffer) {
                // soporta 'H:i:s' o 'H:i'
                $ini = strlen($c->hora_inicio) === 5
                    ? Carbon::createFromFormat('H:i', $c->hora_inicio)
                    : Carbon::createFromFormat('H:i:s', $c->hora_inicio);
                $fin = strlen($c->hora_fin) === 5
                    ? Carbon::createFromFormat('H:i', $c->hora_fin)
                    : Carbon::createFromFormat('H:i:s', $c->hora_fin);

                $ini = $ini->copy()->subMinutes($buffer);
                $fin = $fin->copy()->addMinutes($buffer);

                return [
                    'ini' => $ini->format('H:i'),
                    'fin' => $fin->format('H:i'),
                ];
            })
            ->values();

        $ventanaIni = Carbon::createFromFormat('H:i', $desde);
        $ventanaFin = Carbon::createFromFormat('H:i', $hasta);

        // Si no hay ocupadas, toda la ventana es un hueco (si cumple longitud)
        if ($ocupadas->isEmpty()) {
            if ($ventanaIni->diffInMinutes($ventanaFin) >= $minBlock) {
                return response()->json([
                    'available' => true,
                    'slots' => [
                        [
                            'start' => $ventanaIni->format('H:i'),
                            'end' => $ventanaFin->format('H:i'),
                        ]
                    ]
                ]);
            }
            return response()->json(['available' => false, 'slots' => []]);
        }

        // 3) Fusionar intervalos solapados (ya con buffer)
        $arr = $ocupadas->toArray();
        usort($arr, fn($a, $b) => strcmp($a['ini'], $b['ini']));
        $merged = [];
        foreach ($arr as $blk) {
            if (empty($merged)) {
                $merged[] = $blk;
                continue;
            }
            $last = &$merged[count($merged) - 1];
            if ($blk['ini'] <= $last['fin']) {
                if ($blk['fin'] > $last['fin'])
                    $last['fin'] = $blk['fin'];
            } else {
                $merged[] = $blk;
            }
            unset($last);
        }

        // 4) Calcular huecos entre bloques y bordes de ventana
        $slots = [];
        $cursor = $ventanaIni->copy();

        foreach ($merged as $blk) {
            $blkIni = Carbon::createFromFormat('H:i', max($blk['ini'], $ventanaIni->format('H:i')));
            // hueco antes del bloque
            if ($cursor->lt($blkIni) && $cursor->diffInMinutes($blkIni) >= $minBlock) {
                $slots[] = [
                    'start' => $cursor->format('H:i'),
                    'end' => $blkIni->format('H:i')
                ];
            }
            // mover cursor al fin del bloque
            $cursor = Carbon::createFromFormat('H:i', max($blk['fin'], $cursor->format('H:i')));
            if ($cursor->gt($ventanaFin))
                break;
        }

        // hueco final
        if ($cursor->lt($ventanaFin) && $cursor->diffInMinutes($ventanaFin) >= $minBlock) {
            $slots[] = [
                'start' => $cursor->format('H:i'),
                'end' => $ventanaFin->format('H:i')
            ];
        }

        return response()->json([
            'available' => !empty($slots),
            'slots' => $slots,
        ]);
    }

}

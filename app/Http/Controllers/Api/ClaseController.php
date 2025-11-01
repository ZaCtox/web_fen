<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\ClaseSesion;
use App\Models\Course;
use App\Models\Period;
use App\Models\Room;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    /**
     * Listar todas las clases (con curso, magíster, período, sala y sesiones).
     * Filtros opcionales: anio_ingreso, anio, trimestre, magister (nombre), room_id
     */
    public function index(Request $request)
    {
        $query = Clase::with(['course.magister', 'period', 'room', 'sesiones']);

        if ($request->filled('anio_ingreso')) {
            $query->whereHas('period', function ($q) use ($request) {
                $q->where('anio_ingreso', $request->anio_ingreso);
            });
        }

        if ($request->filled('anio')) {
            $query->whereHas('period', function ($q) use ($request) {
                $q->where('anio', $request->anio);
            });
        }

        if ($request->filled('trimestre')) {
            $query->whereHas('period', function ($q) use ($request) {
                $q->where('numero', $request->trimestre);
            });
        }

        if ($request->filled('magister')) {
            $query->whereHas('course.magister', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->magister . '%');
            });
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $perPage = $request->get('per_page', 15);
        $clases = $query->orderBy('period_id')
            ->orderBy('id')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $clases,
            'message' => 'Clases obtenidas exitosamente'
        ]);
    }

    /**
     * Recursos para el wizard (courses, periods, rooms).
     */
    public function resources()
    {
        $courses = Course::with(['magister:id,nombre,color'])
            ->select('id', 'nombre', 'magister_id', 'period_id')
            ->orderBy('nombre')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'magister' => $c->magister ? [
                    'id' => $c->magister->id,
                    'nombre' => $c->magister->nombre,
                    'color' => $c->magister->color,
                ] : null,
            ]);

        $periods = Period::orderByDesc('anio')->orderBy('numero')
            ->get(['id', 'numero', 'anio'])
            ->map(fn ($p) => ['id' => $p->id, 'numero' => $p->numero, 'anio' => $p->anio]);

        $rooms = Room::orderBy('name')
            ->get(['id', 'name', 'location', 'capacity'])
            ->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'location' => $r->location,
                'capacity' => $r->capacity,
            ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'courses' => $courses,
                'periods' => $periods,
                'rooms' => $rooms,
            ]
        ]);
    }

    /**
     * Crear nueva clase (acepta arreglo de sesiones).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'tipo' => 'required|string|max:50',
            'period_id' => 'required|exists:periods,id',
            'room_id' => 'nullable|exists:rooms,id',
            'url_zoom' => 'nullable|url',
            'encargado' => 'nullable|string|max:255',

            // sesiones (opcional)
            'sesiones' => 'sometimes|array|min:1',
            'sesiones.*.fecha' => 'required|date_format:Y-m-d',
            'sesiones.*.dia' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'sesiones.*.hora_inicio' => 'required|date_format:H:i',
            'sesiones.*.hora_fin' => 'required|date_format:H:i',
            'sesiones.*.modalidad' => 'required|in:online,hibrida,presencial',
            'sesiones.*.room_id' => 'nullable|exists:rooms,id',
            'sesiones.*.url_zoom' => 'nullable|url',
            'sesiones.*.observaciones' => 'nullable|string|max:2000',
            'sesiones.*.estado' => 'nullable|in:pendiente,confirmada,cancelada',
            'sesiones.*.numero_sesion' => 'nullable|integer|min:1',
        ]);

        $clase = Clase::create([
            'course_id' => $validated['course_id'],
            'tipo' => $validated['tipo'],
            'period_id' => $validated['period_id'],
            'room_id' => $validated['room_id'] ?? null,
            'url_zoom' => $validated['url_zoom'] ?? null,
            'encargado' => $validated['encargado'] ?? null,
        ]);

        if (!empty($validated['sesiones']) && is_array($validated['sesiones'])) {
            foreach ($validated['sesiones'] as $i => $sesion) {
                // Validación de regla "fin > inicio"
                if (($sesion['hora_inicio'] ?? null) && ($sesion['hora_fin'] ?? null)) {
                    if (self::diffMinutes($sesion['hora_inicio'], $sesion['hora_fin']) <= 0) {
                        return response()->json([
                            'status' => 'error',
                            'message' => "Sesión " . ($i + 1) . ": La hora fin debe ser posterior a la hora inicio"
                        ], 422);
                    }
                }

                $payload = [
                    'fecha' => $sesion['fecha'],
                    'dia' => $sesion['dia'],
                    'hora_inicio' => $sesion['hora_inicio'],
                    'hora_fin' => $sesion['hora_fin'],
                    'modalidad' => $sesion['modalidad'],
                    'room_id' => $sesion['room_id'] ?? ($validated['room_id'] ?? null),
                    'url_zoom' => $sesion['url_zoom'] ?? ($validated['url_zoom'] ?? null),
                    'observaciones' => $sesion['observaciones'] ?? null,
                    'estado' => $sesion['estado'] ?? 'pendiente',
                    'numero_sesion' => $sesion['numero_sesion'] ?? ($i + 1),
                ];
                $clase->sesiones()->create($payload);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Clase creada correctamente.',
            'data' => $clase->load(['course.magister', 'period', 'room', 'sesiones'])
        ], 201);
    }

    /**
     * Mostrar una clase específica.
     */
    public function show(Clase $clase)
    {
        return response()->json([
            'status' => 'success',
            'data' => $clase->load(['course.magister', 'period', 'room', 'sesiones'])
        ]);
    }

    /**
     * Actualizar clase (datos generales).
     */
    public function update(Request $request, Clase $clase)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'tipo' => 'required|string|max:50',
            'period_id' => 'required|exists:periods,id',
            'room_id' => 'nullable|exists:rooms,id',
            'url_zoom' => 'nullable|url',
            'encargado' => 'nullable|string|max:255'
        ]);

        $clase->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Clase actualizada correctamente.',
            'data' => $clase->load(['course.magister', 'period', 'room', 'sesiones'])
        ]);
    }

    /**
     * Eliminar clase.
     */
    public function destroy(Clase $clase)
    {
        $clase->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Clase eliminada correctamente.'
        ]);
    }

    /**
     * Listado optimizado (paginación manual).
     */
    public function simple(Request $request)
    {
        try {
            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 30);

            $clases = Clase::select([
                'id', 'course_id', 'tipo', 'period_id', 'room_id', 'url_zoom', 'encargado', 'created_at', 'updated_at'
            ])
                ->with([
                    'course:id,nombre,magister_id',
                    'period:id,numero,anio',
                    'room:id,name'
                ])
                ->orderBy('period_id')
                ->orderBy('id')
                ->offset(($page - 1) * $perPage)
                ->limit($perPage)
                ->get();

            $total = Clase::count();
            $lastPage = (int) ceil($total / $perPage);

            $courseIds = $clases->pluck('course_id')->unique()->filter();
            $coursesWithMagisters = Course::select('id', 'nombre', 'magister_id')
                ->with('magister:id,nombre,color')
                ->whereIn('id', $courseIds)
                ->get()
                ->keyBy('id');

            foreach ($clases as $clase) {
                if ($clase->course_id && isset($coursesWithMagisters[$clase->course_id])) {
                    $clase->course = $coursesWithMagisters[$clase->course_id];
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $clases,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => $lastPage,
                    'has_more_pages' => $page < $lastPage,
                    'from' => (($page - 1) * $perPage) + 1,
                    'to' => min($page * $perPage, $total)
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error("Error en endpoint simple: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener clases: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Diagnóstico rápido.
     */
    public function debug()
    {
        $totalClases = Clase::count();
        $totalConRelaciones = Clase::with(['course.magister', 'period', 'room'])->count();

        return response()->json([
            'total_clases' => $totalClases,
            'total_con_relaciones' => $totalConRelaciones,
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true)
        ]);
    }

    /**
     * Público (sin auth). Filtros: magister, sala, dia, period_id, anio_ingreso (opcional).
     */
    public function publicIndex(Request $request)
    {
        try {
            $filters = $request->only('magister', 'sala', 'dia', 'period_id', 'anio_ingreso');
            $query = Clase::with(['course.magister', 'period', 'room']);

            if (!empty($filters['magister'])) {
                $query->whereHas('course.magister', function ($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['magister'] . '%');
                });
            }

            if (!empty($filters['sala'])) {
                $query->whereHas('room', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['sala'] . '%');
                });
            }

            if (!empty($filters['period_id'])) {
                $query->where('period_id', $filters['period_id']);
            }

            if (!empty($filters['anio_ingreso'])) {
                $query->whereHas('period', function ($q) use ($filters) {
                    $q->where('anio_ingreso', $filters['anio_ingreso']);
                });
            }

            $perPage = $request->get('per_page', 20);
            $clases = $query->orderBy('period_id')
                ->orderBy('id')
                ->paginate($perPage);

            $formattedClases = $clases->map(function ($clase) {
                return [
                    'id' => $clase->id,
                    'course_id' => $clase->course_id,
                    'tipo' => $clase->tipo,
                    'period_id' => $clase->period_id,
                    'room_id' => $clase->room_id,
                    'url_zoom' => $clase->url_zoom,
                    'encargado' => $clase->encargado,
                    'course' => $clase->course ? [
                        'id' => $clase->course->id,
                        'nombre' => $clase->course->nombre,
                        'magister' => $clase->course->magister ? [
                            'id' => $clase->course->magister->id,
                            'nombre' => $clase->course->magister->nombre,
                            'color' => $clase->course->magister->color,
                        ] : null,
                    ] : null,
                    'period' => $clase->period ? [
                        'id' => $clase->period->id,
                        'numero' => $clase->period->numero,
                        'anio' => $clase->period->anio,
                    ] : null,
                    'room' => $clase->room ? [
                        'id' => $clase->room->id,
                        'name' => $clase->room->name,
                        'location' => $clase->room->location ?? null,
                        'capacity' => $clase->room->capacity ?? null,
                    ] : null,
                    'public_view' => true,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $formattedClases,
                'pagination' => [
                    'current_page' => $clases->currentPage(),
                    'per_page' => $clases->perPage(),
                    'total' => $clases->total(),
                    'last_page' => $clases->lastPage(),
                    'has_more_pages' => $clases->hasMorePages(),
                    'from' => $clases->firstItem(),
                    'to' => $clases->lastItem(),
                ],
                'message' => 'Clases obtenidas exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cargar las clases: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Público: Mostrar clase.
     */
    public function publicShow($id)
    {
        try {
            $clase = Clase::with(['course.magister', 'period', 'room'])->find($id);
            if (!$clase) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Clase no encontrada'
                ], 404);
            }

            $formattedClase = [
                'id' => $clase->id,
                'course_id' => $clase->course_id,
                'tipo' => $clase->tipo,
                'period_id' => $clase->period_id,
                'room_id' => $clase->room_id,
                'url_zoom' => $clase->url_zoom,
                'encargado' => $clase->encargado,
                'course' => $clase->course ? [
                    'id' => $clase->course->id,
                    'nombre' => $clase->course->nombre,
                    'magister' => $clase->course->magister ? [
                        'id' => $clase->course->magister->id,
                        'nombre' => $clase->course->magister->nombre,
                        'color' => $clase->course->magister->color,
                    ] : null,
                ] : null,
                'period' => $clase->period ? [
                    'id' => $clase->period->id,
                    'numero' => $clase->period->numero,
                    'anio' => $clase->period->anio,
                ] : null,
                'room' => $clase->room ? [
                    'id' => $clase->room->id,
                    'name' => $clase->room->name,
                    'location' => $clase->room->location ?? null,
                    'capacity' => $clase->room->capacity ?? null,
                ] : null,
                'public_view' => true,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $formattedClase,
                'message' => 'Clase obtenida exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cargar la clase: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disponibilidad de sala (paridad con web).
     */
    public function disponibilidad(Request $request)
    {
        $roomId = $request->query('room_id');
        $dia = $request->query('dia');
        $horaInicio = $request->query('hora_inicio');
        $horaFin = $request->query('hora_fin');
        $modality = $request->query('modality');
        $excludeSesionId = $request->query('exclude_sesion_id');

        if ($modality === 'online' || !$dia || !$horaInicio || !$horaFin || !$roomId) {
            return response()->json(['available' => true, 'conflicts' => []]);
        }

        $conflicts = ClaseSesion::with(['clase.course.magister', 'room'])
            ->where('room_id', $roomId)
            ->where('dia', $dia)
            ->when($excludeSesionId, fn($q) => $q->where('id', '!=', $excludeSesionId))
            ->where(function ($q) use ($horaInicio, $horaFin) {
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
                    'hora_inicio' => substr($sesion->hora_inicio, 0, 5),
                    'hora_fin' => substr($sesion->hora_fin, 0, 5),
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

    /**
     * Horarios disponibles (paridad con web).
     */
    public function horarios(Request $request)
    {
        $roomId = $request->query('room_id');
        $dia = $request->query('dia');
        $modality = $request->query('modality');
        $excludeId = $request->query('exclude_id');
        $desde = $request->query('desde', '08:00');
        $hasta = $request->query('hasta', '22:00');
        $minBlock = max(60, (int) $request->query('min_block', 60));
        $buffer = 10;

        if ($modality === 'online') {
            return response()->json(['slots' => [[ 'start' => $desde, 'end' => $hasta ]]]); // no requiere sala
        }
        if (!$roomId || !$dia) {
            return response()->json(['slots' => []]);
        }

        $ocupadas = ClaseSesion::where('room_id', $roomId)
            ->where('dia', $dia)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('hora_inicio')
            ->get(['hora_inicio', 'hora_fin']);

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

        return response()->json(['slots' => $slots, 'block_minutes' => 60, 'edge_buffer' => $buffer]);
    }

    /**
     * Salas alternativas disponibles (paridad con web).
     */
    public function salasDisponibles(Request $request)
    {
        $dia = $request->query('dia');
        $horaInicio = $request->query('hora_inicio');
        $horaFin = $request->query('hora_fin');
        $modalidad = $request->query('modalidad');

        if ($modalidad === 'online' || !$dia || !$horaInicio || !$horaFin) {
            return response()->json(['salas' => []]);
        }

        $salasOcupadas = ClaseSesion::where('dia', $dia)
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where('hora_inicio', '<', $horaFin)
                  ->where('hora_fin', '>', $horaInicio);
            })
            ->pluck('room_id')
            ->unique()
            ->toArray();

        $salas = Room::orderBy('name')->get()
            ->filter(fn ($s) => !in_array($s->id, $salasOcupadas))
            ->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'location' => $s->location ?? 'Sin ubicación',
                'capacity' => $s->capacity ?? 0,
            ])
            ->values();

        return response()->json(['salas' => $salas]);
    }

    // Helpers de tiempo (HH:MM)
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
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private function authorizeAccess()
    {
        if (! tieneRol(['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }

    /**
     * Listar eventos (manuales y generados por clases) con paginación y límites.
     */
    public function index(Request $request)
    {
        $magisterId = $request->query('magister_id');
        $roomId = $request->query('room_id');
        $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : null;
        $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : null;

        // Límites para evitar JSON demasiado grande
        $maxEvents = 50; // Máximo 50 eventos por respuesta (reducido)
        $maxDays = 15; // Máximo 15 días de rango (reducido)

        // Validar rango de fechas
        if ($rangeStart && $rangeEnd) {
            $daysDiff = $rangeStart->diffInDays($rangeEnd);
            if ($daysDiff > $maxDays) {
                return response()->json([
                    'status' => 'error',
                    'message' => "El rango de fechas no puede ser mayor a {$maxDays} días. Días solicitados: {$daysDiff}",
                ], 400);
            }
        }

        // Obtener eventos manuales
        $manualEvents = Event::with(['room', 'magister'])
            ->when($roomId, fn ($q) => $q->where('room_id', $roomId))
            ->when($rangeStart, fn ($q) => $q->where('end_time', '>=', $rangeStart))
            ->when($rangeEnd, fn ($q) => $q->where('start_time', '<=', $rangeEnd))
            ->when($magisterId, fn ($q) => $q->where(function ($query) use ($magisterId) {
                $query->whereNull('magister_id')->orWhere('magister_id', $magisterId);
            }))
            ->limit(25) // Limitar eventos manuales a 25
            ->get()
            ->map(function ($event) {
                $color = is_object($event->magister) ? ($event->magister->color ?? '#a5f63b') : '#a5f63b';

                return [
                    'id' => 'event-'.$event->id,
                    'title' => $event->title,
                    'start' => $event->start_time,
                    'end' => $event->end_time,
                    'description' => $event->description,
                    'magister' => $event->magister ? [
                        'id' => $event->magister->id,
                        'name' => $event->magister->nombre,
                    ] : null,
                    'room' => $event->room ? [
                        'id' => $event->room->id,
                        'name' => $event->room->name,
                    ] : null,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'editable' => Auth::check(),
                    'type' => 'manual',
                ];
            });

        // Obtener eventos de clases con límites
        $classEvents = $this->generarEventosDesdeClasesOptimizado($magisterId, $roomId, $rangeStart, $rangeEnd, 25);

        $allEvents = collect($manualEvents)->concat(collect($classEvents))->values();

        // Limitar el total de eventos
        if ($allEvents->count() > $maxEvents) {
            $allEvents = $allEvents->take($maxEvents);
        }

        return response()->json([
            'status' => 'success',
            'data' => $allEvents,
            'meta' => [
                'total' => $allEvents->count(),
                'max_events' => $maxEvents,
                'range_start' => $rangeStart?->toDateString(),
                'range_end' => $rangeEnd?->toDateString(),
            ],
        ]);
    }

    /**
     * Crear un evento.
     */
    public function store(Request $request)
    {
        

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'magister_id' => 'nullable|exists:magisters,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'room_id' => 'nullable|exists:rooms,id',
            'type' => 'nullable|string|max:255',
        ]);

        $validated['start_time'] = Carbon::parse($validated['start_time']);
        $validated['end_time'] = Carbon::parse($validated['end_time']);
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'activo';

        $event = Event::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Evento creado correctamente.',
            'data' => $event,
        ], 201);
    }

    /**
     * Actualizar un evento.
     */
    public function update(Request $request, Event $event)
    {
        

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'magister_id' => 'nullable|exists:magisters,id',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after_or_equal:start_time',
            'room_id' => 'nullable|exists:rooms,id',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|string',
        ]);

        if (isset($validated['start_time'])) {
            $validated['start_time'] = Carbon::parse($validated['start_time']);
        }
        if (isset($validated['end_time'])) {
            $validated['end_time'] = Carbon::parse($validated['end_time']);
        }

        $event->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Evento actualizado.',
            'data' => $event,
        ]);
    }

    /**
     * Eliminar un evento.
     */
    public function destroy(Event $event)
    {
        
        $event->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Evento eliminado.',
        ]);
    }

    /**
     * Generar eventos desde clases con optimizaciones para evitar JSON demasiado grande.
     */
    private function generarEventosDesdeClasesOptimizado(?string $magisterId = '', $roomId = null, ?Carbon $rangeStart = null, ?Carbon $rangeEnd = null, int $maxEvents = 50)
    {
        $dias = [
            'Domingo' => 0,
            'Lunes' => 1,
            'Martes' => 2,
            'Miércoles' => 3,
            'Jueves' => 4,
            'Viernes' => 5,
            'Sábado' => 6,
        ];

        // Validar que tenemos un rango de fechas
        if (! $rangeStart || ! $rangeEnd) {
            return collect();
        }

        $q = Clase::with(['room', 'period', 'course.magister'])
            ->when(! empty($magisterId), fn ($q) => $q->whereHas('course', fn ($qq) => $qq->where('magister_id', $magisterId)))
            ->when(! empty($roomId), fn ($q) => $q->where('room_id', $roomId))
            ->whereHas('period', fn ($qq) => $qq
                ->whereDate('fecha_fin', '>=', $rangeStart->toDateString())
                ->whereDate('fecha_inicio', '<=', $rangeEnd->toDateString()));

        $clases = $q->get();
        $eventos = collect();
        $eventCount = 0;

        foreach ($clases as $clase) {
            if ($eventCount >= $maxEvents) {
                break; // Salir si ya alcanzamos el límite
            }

            if (! $clase->period || ! $clase->course || ! $clase->hora_inicio || ! $clase->hora_fin || ! isset($dias[$clase->dia])) {
                continue;
            }

            $dayNumber = $dias[$clase->dia];
            $inicioPeriodo = Carbon::parse($clase->period->fecha_inicio);
            $finPeriodo = Carbon::parse($clase->period->fecha_fin);
            $desde = $rangeStart->copy()->max($inicioPeriodo);
            $hasta = $rangeEnd->copy()->min($finPeriodo);

            if ($desde->gt($hasta)) {
                continue;
            }

            $fecha = $desde->copy();
            if ($fecha->dayOfWeek !== $dayNumber) {
                $fecha = $fecha->next($dayNumber);
            }
            if ($fecha->lt($desde)) {
                $fecha->addWeek();
            }

            $magister = $clase->course->magister;
            $color = $magister->color ?? '#6b7280';
            $modality = $clase->modality;
            $online = $modality === 'online';
            $hibrida = $modality === 'hibrida';

            // Generar eventos solo para las fechas en el rango solicitado
            while ($fecha->lte($hasta) && $eventCount < $maxEvents) {
                $start = $fecha->copy()->setTimeFromTimeString($clase->hora_inicio);
                $end = $fecha->copy()->setTimeFromTimeString($clase->hora_fin);

                $titulo = $clase->course->nombre;
                if ($online) {
                    $titulo .= ' [ONLINE]';
                } elseif ($hibrida) {
                    $titulo .= ' [HÍBRIDA]';
                }

                $descripcion = 'Magíster: '.($magister->nombre ?? 'Desconocido');
                if (! empty($clase->url_zoom)) {
                    $descripcion .= "\n🔗 ".$clase->url_zoom;
                }

                $eventos->push([
                    'id' => 'clase-'.$clase->id.'-'.$start->format('Ymd'),
                    'title' => $titulo,
                    'description' => $descripcion,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'room_id' => $clase->room_id,
                    'room' => $clase->room ? ['id' => $clase->room->id, 'name' => $clase->room->name] : null,
                    'editable' => false,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'type' => 'clase',
                    'magister' => $magister ? ['id' => $magister->id, 'name' => $magister->nombre] : null,
                    'modality' => $modality,
                    'url_zoom' => $clase->url_zoom,
                    'profesor' => $clase->encargado ?? null,
                ]);

                $eventCount++;
                $fecha->addWeek();
            }
        }

        return $eventos;
    }

    public function calendario(Request $request)
    {
        $magisterId = $request->query('magister_id');
        $roomId = $request->query('room_id');
        $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : null;
        $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : null;

        // Si no se especifica rango, usar el mes actual
        if (! $rangeStart || ! $rangeEnd) {
            $rangeStart = now()->startOfMonth();
            $rangeEnd = now()->endOfMonth();
        }

        // Límites para la app móvil
        $maxEvents = 100; // Más eventos para la app móvil
        $maxDays = 30; // Un mes completo

        // Validar rango de fechas
        if ($rangeStart && $rangeEnd) {
            $daysDiff = $rangeStart->diffInDays($rangeEnd);
            if ($daysDiff > $maxDays) {
                return response()->json([
                    'status' => 'error',
                    'message' => "El rango de fechas no puede ser mayor a {$maxDays} días. Días solicitados: {$daysDiff}",
                ], 400);
            }
        }

        // Obtener eventos manuales
        $manualEvents = Event::with(['room', 'magister'])
            ->when($roomId, fn ($q) => $q->where('room_id', $roomId))
            ->when($rangeStart, fn ($q) => $q->where('end_time', '>=', $rangeStart))
            ->when($rangeEnd, fn ($q) => $q->where('start_time', '<=', $rangeEnd))
            ->when($magisterId, fn ($q) => $q->where(function ($query) use ($magisterId) {
                $query->whereNull('magister_id')->orWhere('magister_id', $magisterId);
            }))
            ->limit(50) // Más eventos manuales para la app
            ->get()
            ->map(function ($event) {
                $color = is_object($event->magister) ? ($event->magister->color ?? '#12C6DF') : '#12C6DF';

                return [
                    'id' => 'event-'.$event->id,
                    'title' => $event->title,
                    'start' => $event->start_time,
                    'end' => $event->end_time,
                    'description' => $event->description,
                    'magister' => $event->magister ? [
                        'id' => $event->magister->id,
                        'name' => $event->magister->nombre,
                    ] : null,
                    'room' => $event->room ? [
                        'id' => $event->room->id,
                        'name' => $event->room->name,
                    ] : null,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'editable' => Auth::check(),
                    'type' => 'manual',
                ];
            });

        // Obtener eventos de clases
        $classEvents = $this->generarEventosDesdeClasesOptimizado($magisterId, $roomId, $rangeStart, $rangeEnd, 50);

        $allEvents = collect($manualEvents)->concat(collect($classEvents))->values();

        // Limitar el total de eventos
        if ($allEvents->count() > $maxEvents) {
            $allEvents = $allEvents->take($maxEvents);
        }

        return response()->json([
            'status' => 'success',
            'data' => $allEvents,
            'meta' => [
                'total' => $allEvents->count(),
                'max_events' => $maxEvents,
                'range_start' => $rangeStart->toDateString(),
                'range_end' => $rangeEnd->toDateString(),
                'app_version' => 'mobile',
            ],
        ]);
    }

    // ===== MÉTODO PÚBLICO (SIN AUTENTICACIÓN) =====
    public function publicIndex(Request $request)
    {
        $magisterId = $request->query('magister_id');
        $roomId = $request->query('room_id');
        $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : null;
        $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : null;

        // Límites para la vista pública
        $maxEvents = 50;
        $maxDays = 15; // Máximo 15 días para la vista pública

        // Validar rango de fechas
        if ($rangeStart && $rangeEnd) {
            $daysDiff = $rangeStart->diffInDays($rangeEnd);
            if ($daysDiff > $maxDays) {
                return response()->json([
                    'status' => 'error',
                    'message' => "El rango de fechas no puede ser mayor a {$maxDays} días. Días solicitados: {$daysDiff}",
                ], 400);
            }
        }

        // Obtener eventos manuales (solo los públicos)
        $manualEvents = Event::with(['room', 'magister'])
            ->when($roomId, fn ($q) => $q->where('room_id', $roomId))
            ->when($rangeStart, fn ($q) => $q->where('end_time', '>=', $rangeStart))
            ->when($rangeEnd, fn ($q) => $q->where('start_time', '<=', $rangeEnd))
            ->when($magisterId, fn ($q) => $q->where(function ($query) use ($magisterId) {
                $query->whereNull('magister_id')->orWhere('magister_id', $magisterId);
            }))
            ->limit(25)
            ->get()
            ->map(function ($event) {
                $color = is_object($event->magister) ? ($event->magister->color ?? '#12C6DF') : '#12C6DF';

                return [
                    'id' => 'event-'.$event->id,
                    'title' => $event->title,
                    'start' => $event->start_time,
                    'end' => $event->end_time,
                    'description' => $event->description,
                    'magister' => $event->magister ? [
                        'id' => $event->magister->id,
                        'name' => $event->magister->nombre,
                    ] : null,
                    'room' => $event->room ? [
                        'id' => $event->room->id,
                        'name' => $event->room->name,
                    ] : null,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'editable' => false, // No editable en vista pública
                    'type' => 'manual',
                ];
            });

        // Obtener eventos de clases
        $classEvents = $this->generarEventosDesdeClasesOptimizado($magisterId, $roomId, $rangeStart, $rangeEnd, 25);

        $allEvents = collect($manualEvents)->concat(collect($classEvents))->values();

        // Limitar el total de eventos
        if ($allEvents->count() > $maxEvents) {
            $allEvents = $allEvents->take($maxEvents);
        }

        return response()->json([
            'status' => 'success',
            'data' => $allEvents,
            'meta' => [
                'total' => $allEvents->count(),
                'max_events' => $maxEvents,
                'range_start' => $rangeStart?->toDateString(),
                'range_end' => $rangeEnd?->toDateString(),
                'public_view' => true,
            ],
        ]);
    }
}

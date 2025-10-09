<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Period;
use Exception;

class EventController extends Controller
{
    /**
     * Obtener eventos para el calendario (API)
     */
    public function index(Request $request)
    {
        try {
            $magisterId = $request->query('magister_id');
            $roomId = $request->query('room_id');

            $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : null;
            $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : null;

            $magisterId = is_numeric($magisterId) ? (int) $magisterId : null;
            $roomId = is_numeric($roomId) ? (int) $roomId : null;

            // Eventos manuales
            $manualEvents = Event::with(['room', 'magister'])
                ->when($roomId, fn($q) => $q->where('room_id', $roomId))
                ->when($rangeStart, fn($q) => $q->where('end_time', '>=', $rangeStart))
                ->when($rangeEnd, fn($q) => $q->where('start_time', '<=', $rangeEnd))
                ->when($magisterId, fn($q) => $q->where(function ($query) use ($magisterId) {
                    $query->whereNull('magister_id')->orWhere('magister_id', $magisterId);
                }))
                ->get()
                ->map(function ($event) {
                    $color = $event->magister->color ?? '#a5f63b';
                    return [
                        'id' => 'event-' . $event->id,
                        'title' => $event->title,
                        'start' => $event->start_time,
                        'end' => $event->end_time,
                        'magister' => $event->magister ? [
                            'id' => $event->magister->id,
                            'name' => $event->magister->nombre
                        ] : null,
                        'room_id' => $event->room_id,
                        'room' => $event->room ? ['id' => $event->room->id, 'name' => $event->room->name] : null,
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'editable' => Auth::check(),
                        'type' => 'manual',
                        'modality' => null,
                    ];
                });

            // Eventos generados desde clases
            $classEvents = $this->generarEventosDesdeClases($magisterId, $roomId, $rangeStart, $rangeEnd);

            return response()->json(collect($manualEvents)->concat(collect($classEvents))->values());

        } catch (Exception $e) {
            Log::error('Error al cargar eventos del calendario: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar eventos'], 500);
        }
    }

    /**
     * Mostrar vista del calendario
     */
    public function calendario()
    {
        try {
            $periodoActual = Period::orderByDesc('anio')->orderByDesc('numero')->first();
            $fechaInicio = optional($periodoActual)->fecha_inicio?->format('Y-m-d') ?? now()->format('Y-m-d');

            return view('calendario.index', compact('fechaInicio'));

        } catch (Exception $e) {
            Log::error('Error al cargar vista de calendario: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el calendario.');
        }
    }

    /**
     * Crear nuevo evento
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'magister_id' => 'nullable|exists:magisters,id',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after_or_equal:start_time',
                'room_id' => 'nullable|exists:rooms,id',
                'type' => 'nullable|string|in:evento,reunión,actividad,otro',
            ]);

            // Validar que la fecha de fin sea posterior a la de inicio
            $startTime = Carbon::parse($validated['start_time']);
            $endTime = Carbon::parse($validated['end_time']);

            if ($endTime->lessThanOrEqualTo($startTime)) {
                return response()->json([
                    'error' => 'La fecha de fin debe ser posterior a la fecha de inicio'
                ], 422);
            }

            // Validar conflictos de horario si se especificó una sala
            if (!empty($validated['room_id'])) {
                $conflicto = Event::where('room_id', $validated['room_id'])
                    ->where('status', '!=', 'cancelado')
                    ->where(function($q) use ($startTime, $endTime) {
                        $q->whereBetween('start_time', [$startTime, $endTime])
                          ->orWhereBetween('end_time', [$startTime, $endTime])
                          ->orWhere(function($qq) use ($startTime, $endTime) {
                              $qq->where('start_time', '<=', $startTime)
                                 ->where('end_time', '>=', $endTime);
                          });
                    })
                    ->exists();

                if ($conflicto) {
                    return response()->json([
                        'error' => 'Ya existe un evento en esta sala durante este horario'
                    ], 422);
                }
            }

            $validated['start_time'] = $startTime;
            $validated['end_time'] = $endTime;
            $validated['created_by'] = Auth::id();
            $validated['status'] = 'activo';

            $event = Event::create($validated);

            Log::info('Evento creado', ['event_id' => $event->id, 'title' => $event->title]);

            return response()->json($event, 201);

        } catch (Exception $e) {
            Log::error('Error al crear evento: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear el evento'], 500);
        }
    }

    /**
     * Actualizar evento existente
     */
    public function update(Request $request, Event $event)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'magister_id' => 'nullable|exists:magisters,id',
                'start_time' => 'sometimes|required|date',
                'end_time' => 'sometimes|required|date|after_or_equal:start_time',
                'room_id' => 'nullable|exists:rooms,id',
                'type' => 'nullable|string|in:evento,reunión,actividad,otro',
                'status' => 'nullable|string|in:activo,cancelado,finalizado',
            ]);

            if (isset($validated['start_time'])) {
                $validated['start_time'] = Carbon::parse($validated['start_time']);
            }
            if (isset($validated['end_time'])) {
                $validated['end_time'] = Carbon::parse($validated['end_time']);
            }

            // Validar conflictos de horario si cambió la sala o el horario
            if (isset($validated['room_id']) || isset($validated['start_time']) || isset($validated['end_time'])) {
                $roomId = $validated['room_id'] ?? $event->room_id;
                $startTime = $validated['start_time'] ?? $event->start_time;
                $endTime = $validated['end_time'] ?? $event->end_time;

                if ($roomId) {
                    $conflicto = Event::where('room_id', $roomId)
                        ->where('id', '!=', $event->id)
                        ->where('status', '!=', 'cancelado')
                        ->where(function($q) use ($startTime, $endTime) {
                            $q->whereBetween('start_time', [$startTime, $endTime])
                              ->orWhereBetween('end_time', [$startTime, $endTime])
                              ->orWhere(function($qq) use ($startTime, $endTime) {
                                  $qq->where('start_time', '<=', $startTime)
                                     ->where('end_time', '>=', $endTime);
                              });
                        })
                        ->exists();

                    if ($conflicto) {
                        return response()->json([
                            'error' => 'Ya existe un evento en esta sala durante este horario'
                        ], 422);
                    }
                }
            }

            $event->update($validated);

            Log::info('Evento actualizado', ['event_id' => $event->id]);

            return response()->json($event);

        } catch (Exception $e) {
            Log::error('Error al actualizar evento: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar el evento'], 500);
        }
    }

    /**
     * Eliminar evento
     */
    public function destroy(Event $event)
    {
        try {
            $titulo = $event->title;
            $event->delete();

            Log::info('Evento eliminado', ['title' => $titulo]);

            return response()->json(['message' => 'Evento eliminado correctamente']);

        } catch (Exception $e) {
            Log::error('Error al eliminar evento: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar el evento'], 500);
        }
    }

    private function generarEventosDesdeClases(?string $magisterId = '', $roomId = null, ?Carbon $rangeStart = null, ?Carbon $rangeEnd = null)
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

        $q = Clase::with(['room', 'period', 'course.magister', 'sesiones'])
            ->when(!empty($magisterId), fn($q) => $q->whereHas('course', fn($qq) => $qq->where('magister_id', $magisterId)))
            ->when(!empty($roomId), fn($q) => $q->where('room_id', $roomId));

        if ($rangeStart && $rangeEnd) {
            $q->whereHas('period', fn($qq) => $qq
                ->whereDate('fecha_fin', '>=', $rangeStart->toDateString())
                ->whereDate('fecha_inicio', '<=', $rangeEnd->toDateString()));
        }

        $clases = $q->get();
        $eventos = collect();

        foreach ($clases as $clase) {
            if (!$clase->period || !$clase->course || !$clase->hora_inicio || !$clase->hora_fin || !isset($dias[$clase->dia])) {
                continue;
            }

            $dayNumber = $dias[$clase->dia];
            $inicioPeriodo = Carbon::parse($clase->period->fecha_inicio);
            $finPeriodo = Carbon::parse($clase->period->fecha_fin);
            $desde = $rangeStart ? $rangeStart->copy()->max($inicioPeriodo) : $inicioPeriodo->copy();
            $hasta = $rangeEnd ? $rangeEnd->copy()->min($finPeriodo) : $finPeriodo->copy();

            if ($desde->gt($hasta))
                continue;

            $fecha = $desde->copy();
            if ($fecha->dayOfWeek !== $dayNumber)
                $fecha = $fecha->next($dayNumber);
            if ($fecha->lt($desde))
                $fecha->addWeek();

            $magister = $clase->course->magister;
            $color = $magister->color ?? '#6b7280';
            $modality = $clase->modality;
            $online = $modality === 'online';
            $hibrida = $modality === 'hibrida';

            while ($fecha->lte($hasta)) {
                $start = $fecha->copy()->setTimeFromTimeString($clase->hora_inicio);
                $end = $fecha->copy()->setTimeFromTimeString($clase->hora_fin);

                $titulo = $clase->course->nombre;
                if ($online)
                    $titulo .= ' [ONLINE]';
                elseif ($hibrida)
                    $titulo .= ' [HIBRIDA]';

                $descripcion = 'Magíster: ' . ($magister->nombre ?? 'Desconocido');
                if (!empty($clase->url_zoom)) {
                    $descripcion .= "\n🔗 " . $clase->url_zoom;
                }

                // Buscar si existe una sesión para esta fecha específica
                $sesion = $clase->sesiones->firstWhere('fecha', $fecha->toDateString());
                $urlGrabacion = $sesion && $sesion->url_grabacion ? $sesion->url_grabacion : null;

                $eventos->push([
                    'id' => 'clase-' . $clase->id . '-' . $start->format('Ymd'),
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
                    'url_grabacion' => $urlGrabacion,  // ✅ Agregado
                    'clase_id' => $clase->id,  // ✅ Agregado para referencia
                ]);

                $fecha->addWeek();
            }
        }

        return $eventos;
    }
}

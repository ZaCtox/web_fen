<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\ClaseSesion;
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
        Log::info('🟢 EventController@index llamado (calendario logueado)', [
            'magister_id' => $request->get('magister_id'),
            'room_id' => $request->get('room_id'),
            'anio_ingreso' => $request->get('anio_ingreso'),
            'anio' => $request->get('anio'),
            'trimestre' => $request->get('trimestre'),
            'start' => $request->get('start'),
            'end' => $request->get('end'),
        ]);

        try {
            $magisterId = $request->query('magister_id');
            $roomId = $request->query('room_id');
            $anioIngreso = $request->query('anio_ingreso');
            $anio = $request->query('anio');
            $trimestre = $request->query('trimestre');

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

            // Eventos generados desde clases (que ahora incluyen sus sesiones)
            $classEvents = $this->generarEventosDesdeClases($magisterId, $roomId, $rangeStart, $rangeEnd, $anioIngreso, $anio, $trimestre);

            Log::debug('📦 Eventos manuales (logueado):', ['count' => $manualEvents->count()]);
            Log::debug('📦 Eventos de clases/sesiones (logueado):', ['count' => $classEvents->count()]);

            return response()->json(collect($manualEvents)->concat(collect($classEvents))->values());

        } catch (Exception $e) {
            Log::error('Error al cargar eventos del calendario: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar eventos'], 500);
        }
    }

    /**
     * Mostrar vista del calendario
     */
    public function calendario(Request $request)
    {
        try {
            // Obtener años de ingreso disponibles
            $aniosIngreso = Period::select('anio_ingreso')
                ->distinct()
                ->whereNotNull('anio_ingreso')
                ->orderBy('anio_ingreso', 'desc')
                ->pluck('anio_ingreso');

            // Año de ingreso seleccionado (por defecto el más reciente)
            $anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());

            // Obtener el primer período del año de ingreso seleccionado
            $primerPeriodo = Period::where('anio_ingreso', $anioIngresoSeleccionado)
                ->orderBy('anio')
                ->orderBy('numero')
                ->first();
            
            $fechaInicio = optional($primerPeriodo)->fecha_inicio?->format('Y-m-d') ?? now()->format('Y-m-d');
            
            // Obtener períodos del año de ingreso seleccionado
            $periodos = Period::where('anio_ingreso', $anioIngresoSeleccionado)
                ->orderBy('anio')
                ->orderBy('numero')
                ->get();

            return view('calendario.index', compact('fechaInicio', 'aniosIngreso', 'anioIngresoSeleccionado', 'periodos'));

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
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para crear eventos.');
        }
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'magister_id' => 'nullable|exists:magisters,id',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after_or_equal:start_time',
                'room_id' => 'nullable|exists:rooms,id',
                'type' => 'nullable|string|in:evento,reunión,actividad,otro,manual',
                'created_by' => 'nullable|exists:users,id',
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
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para actualizar eventos.');
        }
        
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'magister_id' => 'nullable|exists:magisters,id',
                'start_time' => 'sometimes|required|date',
                'end_time' => 'sometimes|required|date|after_or_equal:start_time',
                'room_id' => 'nullable|exists:rooms,id',
                'type' => 'nullable|string|in:evento,reunión,actividad,otro,manual',
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
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para eliminar eventos.');
        }
        
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

    private function generarEventosDesdeClases(?string $magisterId = '', $roomId = null, ?Carbon $rangeStart = null, ?Carbon $rangeEnd = null, ?string $anioIngreso = null, ?string $anio = null, ?string $trimestre = null)
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
            ->when(!empty($roomId), fn($q) => $q->where('room_id', $roomId))
            ->when(!empty($anioIngreso), fn($q) => $q->whereHas('period', fn($qq) => $qq->where('anio_ingreso', $anioIngreso)))
            ->when(!empty($anio), fn($q) => $q->whereHas('period', fn($qq) => $qq->where('anio', $anio)))
            ->when(!empty($trimestre), fn($q) => $q->whereHas('period', fn($qq) => $qq->where('numero', $trimestre)));

        if ($rangeStart && $rangeEnd) {
            $q->whereHas('period', fn($qq) => $qq
                ->whereDate('fecha_fin', '>=', $rangeStart->toDateString())
                ->whereDate('fecha_inicio', '<=', $rangeEnd->toDateString()));
        }

        $clases = $q->get();
        $eventos = collect();

        foreach ($clases as $clase) {
            if (!$clase->period || !$clase->course) {
                continue;
            }

            $magister = $clase->course->magister;
            $color = $magister->color ?? '#6b7280';

            // Iterar sobre las sesiones de esta clase
            foreach ($clase->sesiones as $sesion) {
                // Filtrar por rango de fechas si se especifica
                if ($rangeStart && $rangeEnd) {
                    $fechaSesion = Carbon::parse($sesion->fecha);
                    if ($fechaSesion->lt($rangeStart) || $fechaSesion->gt($rangeEnd)) {
                        continue;
                    }
                }

                // Filtrar por sala si se especifica
                if (!empty($roomId) && $sesion->room_id != $roomId) {
                    continue;
                }

                // Si la sesión tiene campos de breaks simples (nueva forma)
                if ($sesion->coffee_break_inicio || $sesion->lunch_break_inicio) {
                    $modality = $sesion->modalidad ?? 'presencial';
                    $online = $modality === 'online';
                    $hibrida = $modality === 'hibrida';

                    $tituloBase = $clase->course->nombre;
                    if ($online)
                        $tituloBase .= ' [ONLINE]';
                    elseif ($hibrida)
                        $tituloBase .= ' [HÍBRIDA]';

                    $descripcion = 'Magíster: ' . ($magister->nombre ?? 'Desconocido');
                    if (!empty($sesion->url_zoom)) {
                        $descripcion .= "\n🔗 " . $sesion->url_zoom;
                    }

                    // Crear bloques de tiempo en orden cronológico
                    $bloques = [];
                    
                    // 1. Primera parte de la clase (hasta coffee break)
                    if ($sesion->coffee_break_inicio) {
                        $bloques[] = [
                            'start' => $sesion->hora_inicio,
                            'end' => $sesion->coffee_break_inicio,
                            'title' => $tituloBase,
                            'type' => 'clase'
                        ];
                    }
                    
                    // 2. Coffee Break
                    if ($sesion->coffee_break_inicio && $sesion->coffee_break_fin) {
                        $bloques[] = [
                            'start' => $sesion->coffee_break_inicio,
                            'end' => $sesion->coffee_break_fin,
                            'title' => '☕ COFFEE BREAK',
                            'type' => 'coffee_break'
                        ];
                    }
                    
                    // 3. Segunda parte de la clase (después de coffee break hasta lunch break)
                    if ($sesion->coffee_break_fin && $sesion->lunch_break_inicio) {
                        $bloques[] = [
                            'start' => $sesion->coffee_break_fin,
                            'end' => $sesion->lunch_break_inicio,
                            'title' => $tituloBase,
                            'type' => 'clase'
                        ];
                    }
                    
                    // 4. Lunch Break
                    if ($sesion->lunch_break_inicio && $sesion->lunch_break_fin) {
                        $bloques[] = [
                            'start' => $sesion->lunch_break_inicio,
                            'end' => $sesion->lunch_break_fin,
                            'title' => '🍽️ LUNCH BREAK',
                            'type' => 'lunch_break'
                        ];
                    }
                    
                    // 5. Tercera parte de la clase (después de lunch break)
                    if ($sesion->lunch_break_fin) {
                        $bloques[] = [
                            'start' => $sesion->lunch_break_fin,
                            'end' => $sesion->hora_fin,
                            'title' => $tituloBase,
                            'type' => 'clase'
                        ];
                    }
                    
                    // Si no hay breaks, crear un solo bloque de clase
                    if (empty($bloques)) {
                        $bloques[] = [
                            'start' => $sesion->hora_inicio,
                            'end' => $sesion->hora_fin,
                            'title' => $tituloBase,
                            'type' => 'clase'
                        ];
                    }

                    // Crear eventos para cada bloque
                    foreach ($bloques as $index => $bloque) {
                        $start = Carbon::parse($sesion->fecha)->setTimeFromTimeString($bloque['start']);
                        $end = Carbon::parse($sesion->fecha)->setTimeFromTimeString($bloque['end']);
                        
                        $backgroundColor = $color; // Color del magíster por defecto
                        $borderColor = $color;
                        $textColor = '#ffffff';
                        
                        if ($bloque['type'] === 'coffee_break') {
                            $backgroundColor = '#f97316';
                            $borderColor = '#ea580c';
                        } elseif ($bloque['type'] === 'lunch_break') {
                            $backgroundColor = '#dc2626';
                            $borderColor = '#b91c1c';
                        }
                        
                        $eventos->push([
                            'id' => 'bloque-' . $sesion->id . '-' . $index,
                            'title' => $bloque['title'],
                            'description' => $bloque['type'] === 'clase' ? $descripcion : '⏰ ' . ($bloque['type'] === 'coffee_break' ? 'Descanso de 30 minutos' : 'Almuerzo de 1 hora'),
                            'start' => $start->toDateTimeString(),
                            'end' => $end->toDateTimeString(),
                            'room_id' => $sesion->room_id,
                            'room' => $sesion->room ? ['id' => $sesion->room->id, 'name' => $sesion->room->name] : null,
                            'editable' => false,
                            'backgroundColor' => $backgroundColor,
                            'borderColor' => $borderColor,
                            'textColor' => $textColor,
                            'type' => $bloque['type'] === 'clase' ? 'clase' : 'break',
                            'magister' => $magister ? ['id' => $magister->id, 'name' => $magister->nombre] : null,
                            'modality' => $modality,
                            'url_zoom' => $sesion->url_zoom,
                            'profesor' => $magister->nombre ?? null,
                            'url_grabacion' => $sesion->url_grabacion,
                            'clase_id' => $clase->id,
                            'sesion_id' => $sesion->id,
                        ]);
                    }
                }
                // Si la sesión tiene bloques horarios (forma antigua con JSON)
                elseif ($sesion->tiene_bloques) {
                    foreach ($sesion->bloques_horarios as $index => $bloque) {
                        $tipoBloque = $bloque['tipo'] ?? 'clase';
                        
                        $start = Carbon::parse($sesion->fecha)->setTimeFromTimeString($bloque['inicio']);
                        $end = Carbon::parse($sesion->fecha)->setTimeFromTimeString($bloque['fin']);

                        $modality = $sesion->modalidad ?? 'presencial';
                        $online = $modality === 'online';
                        $hibrida = $modality === 'hibrida';

                        // Configurar título y color según el tipo de bloque
                        if ($tipoBloque === 'coffee_break') {
                            $titulo = '☕ Coffee Break';
                            $backgroundColor = '#f59e0b'; // Naranja/ámbar
                            $descripcion = 'Descanso - Coffee Break';
                            $type = 'break';
                        } elseif ($tipoBloque === 'lunch_break') {
                            $titulo = '🍽️ Lunch Break';
                            $backgroundColor = '#ef4444'; // Rojo
                            $descripcion = 'Descanso - Almuerzo';
                            $type = 'break';
                        } else {
                            // Bloque de clase normal
                            $titulo = $clase->course->nombre;
                            if (!empty($bloque['nombre'])) {
                                $titulo .= ' - ' . $bloque['nombre'];
                            }
                            if ($online)
                                $titulo .= ' [ONLINE]';
                            elseif ($hibrida)
                                $titulo .= ' [HÍBRIDA]';
                            
                            $backgroundColor = $color;
                            $descripcion = 'Magíster: ' . ($magister->nombre ?? 'Desconocido');
                            $descripcion .= "\n⏰ Bloque " . ($index + 1) . ": " . $bloque['inicio'] . ' - ' . $bloque['fin'];
                            if (!empty($sesion->url_zoom)) {
                                $descripcion .= "\n🔗 " . $sesion->url_zoom;
                            }
                            $type = 'clase';
                        }

                        $eventos->push([
                            'id' => 'clase-' . $clase->id . '-sesion-' . $sesion->id . '-bloque-' . $index,
                            'title' => $titulo,
                            'description' => $descripcion,
                            'start' => $start->toDateTimeString(),
                            'end' => $end->toDateTimeString(),
                            'room_id' => $sesion->room_id,
                            'room' => $sesion->room ? ['id' => $sesion->room->id, 'name' => $sesion->room->name] : null,
                            'editable' => false,
                            'backgroundColor' => $backgroundColor,
                            'borderColor' => $backgroundColor,
                            'type' => $type,
                            'bloque_tipo' => $tipoBloque,
                            'magister' => $magister ? ['id' => $magister->id, 'name' => $magister->nombre] : null,
                            'modality' => $modality,
                            'url_zoom' => $sesion->url_zoom,
                            'profesor' => $magister->nombre ?? null,
                            'url_grabacion' => $sesion->url_grabacion,
                            'clase_id' => $clase->id,
                            'sesion_id' => $sesion->id,
                            'bloque_index' => $index,
                            'tiene_bloques' => true,
                        ]);
                    }
                } else {
                    // Modo tradicional sin bloques
                    $start = Carbon::parse($sesion->fecha)->setTimeFromTimeString($sesion->hora_inicio);
                    $end = Carbon::parse($sesion->fecha)->setTimeFromTimeString($sesion->hora_fin);

                    $modality = $sesion->modalidad ?? 'presencial';
                    $online = $modality === 'online';
                    $hibrida = $modality === 'hibrida';

                    $titulo = $clase->course->nombre;
                    if ($online)
                        $titulo .= ' [ONLINE]';
                    elseif ($hibrida)
                        $titulo .= ' [HÍBRIDA]';

                    $descripcion = 'Magíster: ' . ($magister->nombre ?? 'Desconocido');
                    if (!empty($sesion->url_zoom)) {
                        $descripcion .= "\n🔗 " . $sesion->url_zoom;
                    }

                    $eventos->push([
                        'id' => 'clase-' . $clase->id . '-sesion-' . $sesion->id,
                        'title' => $titulo,
                        'description' => $descripcion,
                        'start' => $start->toDateTimeString(),
                        'end' => $end->toDateTimeString(),
                        'room_id' => $sesion->room_id,
                        'room' => $sesion->room ? ['id' => $sesion->room->id, 'name' => $sesion->room->name] : null,
                        'editable' => false,
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'type' => 'clase',
                        'magister' => $magister ? ['id' => $magister->id, 'name' => $magister->nombre] : null,
                        'modality' => $modality,
                        'url_zoom' => $sesion->url_zoom,
                        'profesor' => $magister->nombre ?? null,
                        'url_grabacion' => $sesion->url_grabacion,
                        'clase_id' => $clase->id,
                        'sesion_id' => $sesion->id,
                        'tiene_bloques' => false,
                    ]);
                }
            }
        }

        return $eventos;
    }

}










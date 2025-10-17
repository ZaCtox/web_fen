<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\ClaseSesion;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private function authorizeAccess()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->rol, ['docente', 'administrativo', 'administrador'])) {
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
        $anioIngreso = $request->query('anio_ingreso');
        $anio = $request->query('anio');
        $trimestre = $request->query('trimestre');
        $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : null;
        $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : null;

        \Log::info('📅 API EventController@index', [
            'magister_id' => $magisterId,
            'room_id' => $roomId,
            'anio_ingreso' => $anioIngreso,
            'anio' => $anio,
            'trimestre' => $trimestre,
            'start' => $rangeStart?->toDateString(),
            'end' => $rangeEnd?->toDateString(),
        ]);

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
            ->when($roomId, fn($q) => $q->where('room_id', $roomId))
            ->when($rangeStart, fn($q) => $q->where('end_time', '>=', $rangeStart))
            ->when($rangeEnd, fn($q) => $q->where('start_time', '<=', $rangeEnd))
            ->when($magisterId, fn($q) => $q->where(function ($query) use ($magisterId) {
                $query->whereNull('magister_id')->orWhere('magister_id', $magisterId);
            }))
            ->limit(25) // Limitar eventos manuales a 25
            ->get()
            ->map(function ($event) {
                $color = is_object($event->magister) ? ($event->magister->color ?? '#a5f63b') : '#a5f63b';

                return [
                    'id' => 'event-' . $event->id,
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
        $classEvents = $this->generarEventosDesdeClasesOptimizado($magisterId, $roomId, $rangeStart, $rangeEnd, $anioIngreso, $anio, $trimestre, 25);

        // Obtener sesiones de clase
        $sesionEvents = $this->generarEventosDesdeSesiones($magisterId, $roomId, $rangeStart, $rangeEnd, $anioIngreso, $anio, $trimestre, 50);

        $allEvents = collect($manualEvents)->concat($classEvents)->concat($sesionEvents)->values();

        // Limitar el total de eventos
        if ($allEvents->count() > $maxEvents) {
            $allEvents = $allEvents->take($maxEvents);
        }

        \Log::info('📤 Enviando eventos al calendario admin: ' . $allEvents->count());

        // FullCalendar espera un array directo, no un objeto con 'data'
        return response()->json($allEvents);
    }

    /**
     * Crear un evento.
     */
    public function store(Request $request)
    {
        $this->authorizeAccess();

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
        $this->authorizeAccess();

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
        $this->authorizeAccess();

        $event->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Evento eliminado.',
        ]);
    }

    /**
     * Generar eventos desde clases con optimizaciones para evitar JSON demasiado grande.
     */
    private function generarEventosDesdeClasesOptimizado(?string $magisterId = '', $roomId = null, ?Carbon $rangeStart = null, ?Carbon $rangeEnd = null, ?string $anioIngreso = null, ?string $anio = null, ?string $trimestre = null, int $maxEvents = 50)
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
        if (!$rangeStart || !$rangeEnd) {
            return collect();
        }

        $q = Clase::with(['room', 'period', 'course.magister'])
            ->when(!empty($magisterId), fn($q) => $q->whereHas('course', fn($qq) => $qq->where('magister_id', $magisterId)))
            ->when(!empty($roomId), fn($q) => $q->where('room_id', $roomId))
            ->when(!empty($anioIngreso), fn($q) => $q->whereHas('period', fn($qq) => $qq->where('anio_ingreso', $anioIngreso)))
            ->when(!empty($anio), fn($q) => $q->whereHas('period', fn($qq) => $qq->where('anio', $anio)))
            ->when(!empty($trimestre), fn($q) => $q->whereHas('period', fn($qq) => $qq->where('numero', $trimestre)))
            ->whereHas('period', fn($qq) => $qq
                ->whereDate('fecha_fin', '>=', $rangeStart->toDateString())
                ->whereDate('fecha_inicio', '<=', $rangeEnd->toDateString()));

        $clases = $q->get();
        $eventos = collect();
        $eventCount = 0;

        foreach ($clases as $clase) {
            if ($eventCount >= $maxEvents) {
                break; // Salir si ya alcanzamos el límite
            }

            if (!$clase->period || !$clase->course || !$clase->hora_inicio || !$clase->hora_fin || !isset($dias[$clase->dia])) {
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

                $descripcion = 'Magíster: ' . ($magister->nombre ?? 'Desconocido');
                if (!empty($clase->url_zoom)) {
                    $descripcion .= "\n🔗 " . $clase->url_zoom;
                }

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
                ]);

                $eventCount++;
                $fecha->addWeek();
            }
        }

        return $eventos;
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

    public function calendario(Request $request)
    {
        $magisterId = $request->query('magister_id');
        $roomId = $request->query('room_id');
        $anioIngreso = $request->query('anio_ingreso');
        $anio = $request->query('anio');
        $trimestre = $request->query('trimestre');
        $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : null;
        $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : null;

        // Si no se especifica rango, usar el mes actual
        if (!$rangeStart || !$rangeEnd) {
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
            ->when($roomId, fn($q) => $q->where('room_id', $roomId))
            ->when($rangeStart, fn($q) => $q->where('end_time', '>=', $rangeStart))
            ->when($rangeEnd, fn($q) => $q->where('start_time', '<=', $rangeEnd))
            ->when($magisterId, fn($q) => $q->where(function ($query) use ($magisterId) {
                $query->whereNull('magister_id')->orWhere('magister_id', $magisterId);
            }))
            ->limit(50) // Más eventos manuales para la app
            ->get()
            ->map(function ($event) {
                $color = is_object($event->magister) ? ($event->magister->color ?? '#12C6DF') : '#12C6DF';

                return [
                    'id' => 'event-' . $event->id,
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
        $classEvents = $this->generarEventosDesdeClasesOptimizado($magisterId, $roomId, $rangeStart, $rangeEnd, $anioIngreso, $anio, $trimestre, 50);

        // Obtener sesiones de clase
        $sesionEvents = $this->generarEventosDesdeSesiones($magisterId, $roomId, $rangeStart, $rangeEnd, $anioIngreso, $anio, $trimestre, 50);

        $allEvents = collect($manualEvents)->concat($classEvents)->concat($sesionEvents)->values();

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
        $anioIngreso = $request->query('anio_ingreso');
        $anio = $request->query('anio');
        $trimestre = $request->query('trimestre');
        $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : null;
        $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : null;

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
                $color = $event->magister->color ?? '#12C6DF';
                return [
                    'id' => 'event-' . $event->id,
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
                    'editable' => false,
                    'type' => 'manual',
                ];
            });

        // Eventos generados desde clases (usando la lógica del web)
        $classEvents = $this->generarEventosDesdeClases($magisterId, $roomId, $rangeStart, $rangeEnd, $anioIngreso, $anio, $trimestre);

        $allEvents = collect($manualEvents)->concat($classEvents)->values();

        // ✅ CAMBIO: Envolver en objeto con status y data para consistencia
        return response()->json([
            'status' => 'success',
            'data' => $allEvents
        ]);
    }

    /**
     * Generar eventos desde las sesiones de clase (ClaseSesion)
     */
    private function generarEventosDesdeSesiones(?string $magisterId = '', $roomId = null, ?Carbon $rangeStart = null, ?Carbon $rangeEnd = null, ?string $anioIngreso = null, ?string $anio = null, ?string $trimestre = null, int $maxEvents = 50)
    {
        if (!$rangeStart || !$rangeEnd) {
            return collect();
        }

        \Log::info('🔍 Buscando sesiones de clase', [
            'magister_id' => $magisterId,
            'room_id' => $roomId,
            'anio_ingreso' => $anioIngreso,
            'anio' => $anio,
            'trimestre' => $trimestre,
            'fecha_inicio' => $rangeStart->toDateString(),
            'fecha_fin' => $rangeEnd->toDateString(),
        ]);

        $sesiones = ClaseSesion::with(['clase.room', 'clase.period', 'clase.course.magister'])
            ->whereBetween('fecha', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->when(!empty($magisterId), fn($q) => $q->whereHas('clase.course', fn($qq) => $qq->where('magister_id', $magisterId)))
            ->when(!empty($roomId), fn($q) => $q->whereHas('clase', fn($qq) => $qq->where('room_id', $roomId)))
            ->when(!empty($anioIngreso), fn($q) => $q->whereHas('clase.period', fn($qq) => $qq->where('anio_ingreso', $anioIngreso)))
            ->when(!empty($anio), fn($q) => $q->whereHas('clase.period', fn($qq) => $qq->where('anio', $anio)))
            ->when(!empty($trimestre), fn($q) => $q->whereHas('clase.period', fn($qq) => $qq->where('numero', $trimestre)))
            ->limit($maxEvents)
            ->get();

        \Log::info('✅ Sesiones encontradas: ' . $sesiones->count());

        return $sesiones->flatMap(function ($sesion) {
            $clase = $sesion->clase;
            $magister = $clase->course->magister ?? null;
            $magisterNombre = $magister ? $magister->nombre : 'Sin magíster';
            $magisterColor = $magister ? ($magister->color ?? '#6b7280') : '#6b7280';

            $eventos = [];

            // Si la sesión tiene campos de breaks simples (nueva forma - MÁS SIMPLE!)
            if ($sesion->coffee_break_inicio || $sesion->lunch_break_inicio) {
                // Crear evento principal de la clase
                $horaInicio = $sesion->hora_inicio ?? '09:00:00';
                $horaFin = $sesion->hora_fin ?? '16:30:00';

                $start = Carbon::parse($sesion->fecha)->setTimeFromTimeString($horaInicio);
                $end = Carbon::parse($sesion->fecha)->setTimeFromTimeString($horaFin);

                $titulo = $clase->course->nombre;
                $modality = $sesion->modalidad ?? 'presencial';

                if ($modality === 'online') {
                    $titulo .= ' [ONLINE]';
                } elseif ($modality === 'hibrida') {
                    $titulo .= ' [HÍBRIDA]';
                }

                $descripcion = 'Magíster: ' . $magisterNombre;
                if (!empty($sesion->url_zoom)) {
                    $descripcion .= "\n🔗 " . $sesion->url_zoom;
                }

                $eventos[] = [
                    'id' => 'sesion-' . $sesion->id,
                    'title' => $titulo,
                    'description' => $descripcion,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'room_id' => $sesion->room_id,
                    'room' => $sesion->room ? ['id' => $sesion->room->id, 'name' => $sesion->room->name] : null,
                    'editable' => false,
                    'backgroundColor' => $magisterColor,
                    'borderColor' => $magisterColor,
                    'type' => 'clase',
                    'magister' => $magister ? ['id' => $magister->id, 'name' => $magister->nombre] : null,
                    'modality' => $modality,
                    'url_zoom' => $sesion->url_zoom,
                    'profesor' => $clase->encargado ?? null,
                    'sesion_id' => $sesion->id,
                ];

                // Agregar Coffee Break si existe
                if ($sesion->coffee_break_inicio && $sesion->coffee_break_fin) {
                    $eventos[] = [
                        'id' => 'break-coffee-' . $sesion->id,
                        'title' => '☕ COFFEE BREAK',
                        'description' => '⏰ Descanso de 30 minutos',
                        'start' => Carbon::parse($sesion->fecha)->setTimeFromTimeString($sesion->coffee_break_inicio)->toDateTimeString(),
                        'end' => Carbon::parse($sesion->fecha)->setTimeFromTimeString($sesion->coffee_break_fin)->toDateTimeString(),
                        'room_id' => $sesion->room_id,
                        'room' => $sesion->room ? ['id' => $sesion->room->id, 'name' => $sesion->room->name] : null,
                        'editable' => false,
                        'backgroundColor' => '#f97316', // Naranja más vibrante
                        'borderColor' => '#ea580c',
                        'textColor' => '#ffffff',
                        'display' => 'block',
                        'type' => 'break',
                        'sesion_id' => $sesion->id,
                        'allDay' => false,
                    ];
                }

                // Agregar Lunch Break si existe
                if ($sesion->lunch_break_inicio && $sesion->lunch_break_fin) {
                    $eventos[] = [
                        'id' => 'break-lunch-' . $sesion->id,
                        'title' => '🍽️ LUNCH BREAK',
                        'description' => '⏰ Almuerzo de 1 hora',
                        'start' => Carbon::parse($sesion->fecha)->setTimeFromTimeString($sesion->lunch_break_inicio)->toDateTimeString(),
                        'end' => Carbon::parse($sesion->fecha)->setTimeFromTimeString($sesion->lunch_break_fin)->toDateTimeString(),
                        'room_id' => $sesion->room_id,
                        'room' => $sesion->room ? ['id' => $sesion->room->id, 'name' => $sesion->room->name] : null,
                        'editable' => false,
                        'backgroundColor' => '#dc2626', // Rojo más vibrante
                        'borderColor' => '#b91c1c',
                        'textColor' => '#ffffff',
                        'display' => 'block',
                        'type' => 'break',
                        'sesion_id' => $sesion->id,
                        'allDay' => false,
                    ];
                }
            }
            // Si tiene bloques horarios (forma antigua con JSON)
            elseif ($sesion->tiene_bloques) {
                foreach ($sesion->bloques_horarios as $index => $bloque) {
                    $tipoBloque = $bloque['tipo'] ?? 'clase';

                    $start = Carbon::parse($sesion->fecha)->setTimeFromTimeString($bloque['inicio']);
                    $end = Carbon::parse($sesion->fecha)->setTimeFromTimeString($bloque['fin']);

                    $modality = $sesion->modalidad ?? 'presencial';

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

                        if ($modality === 'online') {
                            $titulo .= ' [ONLINE]';
                        } elseif ($modality === 'hibrida') {
                            $titulo .= ' [HÍBRIDA]';
                        }

                        $backgroundColor = $magisterColor;
                        $descripcion = 'Magíster: ' . $magisterNombre;
                        $descripcion .= "\n⏰ Bloque " . ($index + 1) . ": " . $bloque['inicio'] . ' - ' . $bloque['fin'];
                        if (!empty($sesion->url_zoom)) {
                            $descripcion .= "\n🔗 " . $sesion->url_zoom;
                        }
                        $type = 'clase';
                    }

                    $eventos[] = [
                        'id' => 'sesion-' . $sesion->id . '-bloque-' . $index,
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
                        'profesor' => $clase->encargado ?? null,
                        'sesion_id' => $sesion->id,
                        'bloque_index' => $index,
                        'tiene_bloques' => true,
                    ];
                }
            } else {
                // Modo tradicional: usar hora_inicio y hora_fin de la sesión o de la clase
                $horaInicio = $sesion->hora_inicio ?? $clase->hora_inicio ?? '09:00:00';
                $horaFin = $sesion->hora_fin ?? $clase->hora_fin ?? '13:00:00';

                // Fallback: Extraer hora_inicio y hora_fin de las observaciones (retrocompatibilidad)
                preg_match('/\((\d{2}:\d{2}:\d{2})-(\d{2}:\d{2}:\d{2})\)/', $sesion->observaciones ?? '', $matches);
                if (!empty($matches[1])) {
                    $horaInicio = $matches[1];
                    $horaFin = $matches[2];
                }

                $start = Carbon::parse($sesion->fecha)->setTimeFromTimeString($horaInicio);
                $end = Carbon::parse($sesion->fecha)->setTimeFromTimeString($horaFin);

                $titulo = $clase->course->nombre;
                $modality = $sesion->modalidad ?? 'presencial';

                // Verificar modalidad de observaciones (retrocompatibilidad)
                if (str_contains($sesion->observaciones ?? '', 'híbrida')) {
                    $modality = 'hibrida';
                } elseif (str_contains($sesion->observaciones ?? '', 'Clase online')) {
                    $modality = 'online';
                }

                if ($modality === 'online') {
                    $titulo .= ' [ONLINE]';
                } elseif ($modality === 'hibrida') {
                    $titulo .= ' [HÍBRIDA]';
                }

                $descripcion = 'Magíster: ' . $magisterNombre;
                if (!empty($sesion->url_zoom ?? $clase->url_zoom)) {
                    $descripcion .= "\n🔗 " . ($sesion->url_zoom ?? $clase->url_zoom);
                }

                $eventos[] = [
                    'id' => 'sesion-' . $sesion->id,
                    'title' => $titulo,
                    'description' => $descripcion,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'room_id' => $sesion->room_id ?? $clase->room_id,
                    'room' => ($sesion->room ?? $clase->room) ? ['id' => ($sesion->room ?? $clase->room)->id, 'name' => ($sesion->room ?? $clase->room)->name] : null,
                    'editable' => false,
                    'backgroundColor' => $magisterColor,
                    'borderColor' => $magisterColor,
                    'type' => 'clase',
                    'magister' => $magister ? ['id' => $magister->id, 'name' => $magister->nombre] : null,
                    'modality' => $modality,
                    'url_zoom' => $sesion->url_zoom ?? $clase->url_zoom,
                    'profesor' => $clase->encargado ?? null,
                    'sesion_id' => $sesion->id,
                    'tiene_bloques' => false,
                ];
            }

            return $eventos;
        });
    }
}

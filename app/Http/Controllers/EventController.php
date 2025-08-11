<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private $coloresMagister = [
        'Economía' => '#3b82f6',
        'Dirección y Planificación Tributaria' => '#ef4444',
        'Gestión de Sistemas de Salud' => '#10b981',
        'Gestión y Políticas Públicas' => '#f97316',
    ];

    public function index(Request $request)
    {
        $magister = trim((string) $request->query('magister', ''));
        $roomId = $request->query('room_id');
        $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : null;
        $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : null;

        // === Eventos manuales ===
        $manualEvents = Event::with('room')
            ->when(!empty($roomId), fn($q) => $q->where('room_id', $roomId))
            ->when($rangeStart, fn($q) => $q->where('end_time', '>=', $rangeStart))
            ->when($rangeEnd, fn($q) => $q->where('start_time', '<=', $rangeEnd))
            ->get()
            ->map(function ($event) {
                return [
                    'id' => 'event-' . $event->id,
                    'title' => $event->title,
                    'start' => $event->start_time,
                    'end' => $event->end_time,
                    'room_id' => $event->room_id,
                    'room' => $event->room ? ['id' => $event->room->id, 'name' => $event->room->name] : null,
                    'backgroundColor' => '#a5f63b',
                    'borderColor' => '#8add1e',
                    'editable' => Auth::check(),
                    'type' => 'manual',
                    'magister' => null, // No está asociado a un magíster
                    'modality' => null,
                ];
            });

        // === Clases ===
        $classEvents = $this->generarEventosDesdeClases($magister, $roomId, $rangeStart, $rangeEnd);

        return response()->json($manualEvents->concat($classEvents)->values());
    }


    public function store(Request $request)
    {
        $this->autorizar();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
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

        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        $this->autorizar();

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
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

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $this->autorizar();

        $event->delete();

        return response()->json(['message' => 'Evento eliminado']);
    }

    private function autorizar()
    {
        if (!in_array(Auth::user()->rol ?? null, ['docente', 'administrativo'], true)) {
            abort(403, 'Acceso no autorizado.');
        }
    }

    private function generarEventosDesdeClases(?string $magister = '', $roomId = null, ?Carbon $rangeStart = null, ?Carbon $rangeEnd = null)
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

        $q = Clase::with(['room', 'period', 'course.magister'])
            ->when($magister !== '', function ($q) use ($magister) {
                $q->whereHas('course.magister', fn($qq) => $qq->where('nombre', $magister));
            })
            ->when(!empty($roomId), fn($q) => $q->where('room_id', $roomId));

        // Si FullCalendar envía rango, recorta por período para no generar basura
        if ($rangeStart && $rangeEnd) {
            $q->whereHas('period', function ($qq) use ($rangeStart, $rangeEnd) {
                $qq->whereDate('fecha_fin', '>=', $rangeStart->toDateString())
                    ->whereDate('fecha_inicio', '<=', $rangeEnd->toDateString());
            });
        }

        $clases = $q->get();
        $eventos = collect();

        foreach ($clases as $clase) {
            if (!$clase->period || !$clase->course || !$clase->hora_inicio || !$clase->hora_fin || !isset($dias[$clase->dia])) {
                continue;
            }

            $dayNumber = $dias[$clase->dia];

            // Punto de arranque para iterar
            $inicioPeriodo = Carbon::parse($clase->period->fecha_inicio);
            $finPeriodo = Carbon::parse($clase->period->fecha_fin);

            // Si hay rango, úsalo para acotar aún más
            $desde = $rangeStart ? $rangeStart->copy()->max($inicioPeriodo) : $inicioPeriodo->copy();
            $hasta = $rangeEnd ? $rangeEnd->copy()->min($finPeriodo) : $finPeriodo->copy();

            if ($desde->gt($hasta)) {
                continue;
            }

            // Buscar el primer "día de la semana" >= desde
            $fecha = $desde->copy();
            if ($fecha->dayOfWeek !== $dayNumber) {
                $fecha = $fecha->next($dayNumber);
            }
            if ($fecha->lt($desde)) {
                $fecha->addWeek(); // por si next() cayó antes del rango exacto
            }

            $nombreMagister = $clase->course->magister->nombre ?? 'Desconocido';
            $color = $this->coloresMagister[$nombreMagister] ?? '#6b7280';
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

                $descripcion = 'Magíster: ' . $nombreMagister;
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
                    'backgroundColor' => $online ? '#6366f1' : ($hibrida ? '#facc15' : $color),
                    'borderColor' => $online ? '#4338ca' : ($hibrida ? '#eab308' : $color),
                    'type' => 'clase',
                    'magister' => $nombreMagister,
                    'modality' => $modality,
                    'url_zoom' => $clase->url_zoom,
                ]);

                $fecha->addWeek();
            }
        }

        return $eventos;
    }
}

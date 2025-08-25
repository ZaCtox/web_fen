<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Period;

class EventController extends Controller
{
    private function authorizeAccess()
    {
        if (!tieneRol(['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function index(Request $request)
    {
        $magisterId = $request->query('magister_id');
        $roomId = $request->query('room_id');

        $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : null;
        $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : null;

        $magisterId = is_numeric($magisterId) ? (int) $magisterId : null;
        $roomId = is_numeric($roomId) ? (int) $roomId : null;

        $manualEvents = Event::with(['room', 'magister'])
            ->when($roomId, fn($q) => $q->where('room_id', $roomId))
            ->when($rangeStart, fn($q) => $q->where('end_time', '>=', $rangeStart))
            ->when($rangeEnd, fn($q) => $q->where('start_time', '<=', $rangeEnd))
            ->when($magisterId, fn($q) => $q->where(function ($query) use ($magisterId) {
                $query->whereNull('magister_id')->orWhere('magister_id', $magisterId);
            }))
            ->get()
            ->map(function ($event) {
                $color = is_object($event->magister) ? ($event->magister->color ?? '#a5f63b') : '#a5f63b';

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

        $classEvents = $this->generarEventosDesdeClases($magisterId, $roomId, $rangeStart, $rangeEnd);

        return response()->json(collect($manualEvents)->concat(collect($classEvents))->values());
    }


    public function calendario()
    {
        $periodoActual = Period::orderByDesc('anio')->orderByDesc('numero')->first();
        $fechaInicio = optional($periodoActual)->fecha_inicio?->format('Y-m-d') ?? now()->format('Y-m-d');

        return view('calendario.index', compact('fechaInicio'));
    }


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

        return response()->json($event);
    }

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

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $this->authorizeAccess();
        $event->delete();

        return response()->json(['message' => 'Evento eliminado']);
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

        $q = Clase::with(['room', 'period', 'course.magister'])
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

                $fecha->addWeek();
            }
        }

        return $eventos;
    }
}

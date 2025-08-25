<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GuestEventController extends Controller
{
    public function index(Request $request)
    {
        \Log::info('🟡 GuestEventController@index llamado', [
            'magister_id' => $request->get('magister_id'),
            'room_id' => $request->get('room_id'),
            'start' => $request->get('start'),
            'end' => $request->get('end'),
        ]);

        try {
            $magisterId = is_numeric($request->get('magister_id')) ? (int) $request->get('magister_id') : null;
            $roomId = is_numeric($request->get('room_id')) ? (int) $request->get('room_id') : null;

            $rangeStart = $request->query('start') ? Carbon::parse($request->query('start'), 'America/Santiago') : null;
            $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end'), 'America/Santiago') : null;

            if (!$rangeStart || !$rangeEnd) {
                return response()->json([]);
            }

            $dias = [
                'Domingo' => 0,
                'Lunes' => 1,
                'Martes' => 2,
                'Miércoles' => 3,
                'Jueves' => 4,
                'Viernes' => 5,
                'Sábado' => 6,
            ];

            // 🔵 CLASES
            $clases = Clase::with(['room', 'period', 'course.magister'])
                ->when($magisterId, fn($q) => $q->whereHas('course', fn($qq) => $qq->where('magister_id', $magisterId)))
                ->when($roomId, fn($q) => $q->where('room_id', $roomId))
                ->when($rangeStart && $rangeEnd, function ($q) use ($rangeStart, $rangeEnd) {
                    $q->whereHas('period', function ($qq) use ($rangeStart, $rangeEnd) {
                        $qq->whereDate('fecha_fin', '>=', $rangeStart->toDateString())
                            ->whereDate('fecha_inicio', '<=', $rangeEnd->toDateString());
                    });
                })->get();

            $classEvents = collect();

            foreach ($clases as $clase) {
                if (!$clase->period || !$clase->course || !$clase->hora_inicio || !$clase->hora_fin || !isset($dias[$clase->dia]))
                    continue;

                $dayNumber = $dias[$clase->dia];
                $inicio = Carbon::parse($clase->period->fecha_inicio);
                $fin = Carbon::parse($clase->period->fecha_fin);
                $desde = $rangeStart ? $rangeStart->copy()->max($inicio) : $inicio->copy();
                $hasta = $rangeEnd ? $rangeEnd->copy()->min($fin) : $fin->copy();

                if ($desde->gt($hasta))
                    continue;

                $fecha = $desde->copy();
                if ($fecha->dayOfWeek !== $dayNumber) {
                    $fecha = $fecha->next($dayNumber);
                }

                $modality = $clase->modality;
                $magister = $clase->course->magister;
                $magisterNombre = is_object($magister) ? $magister->nombre : 'Sin magíster';
                $magisterColor = is_object($magister) ? ($magister->color ?? '#6b7280') : '#6b7280';

                $titulo = $clase->course->nombre;
                if ($modality === 'online') $titulo .= ' [ONLINE]';
                elseif ($modality === 'hibrida') $titulo .= ' [HIBRIDA]';

                while ($fecha->lte($hasta)) {
                    $start = $fecha->copy()->setTimeFromTimeString($clase->hora_inicio);
                    $end = $fecha->copy()->setTimeFromTimeString($clase->hora_fin);

                    $classEvents->push([
                        'id' => 'clase-' . $clase->id . '-' . $start->format('Ymd'),
                        'title' => $titulo,
                        'description' => 'Magíster: ' . $magisterNombre,
                        'start' => $start->toDateTimeString(),
                        'end' => $end->toDateTimeString(),
                        'room_id' => $clase->room_id,
                        'room' => $clase->room ? ['name' => $clase->room->name] : null,
                        'editable' => false,
                        'backgroundColor' => $magisterColor,
                        'borderColor' => $magisterColor,
                        'type' => 'clase',
                        'magister' => $magisterNombre,
                        'modality' => $modality,
                        'url_zoom' => $clase->url_zoom,
                        'profesor' => $clase->encargado ?? null,
                    ]);

                    $fecha->addWeek();
                }
            }

            // 🔴 EVENTOS MANUALES
            $manualEvents = Event::with(['room', 'magister'])
                ->when($roomId, fn($q) => $q->where('room_id', $roomId))
                ->when($rangeStart, fn($q) => $q->where('end_time', '>=', $rangeStart))
                ->when($rangeEnd, fn($q) => $q->where('start_time', '<=', $rangeEnd))
                ->when($magisterId, fn($q) => $q->where(function ($q2) use ($magisterId) {
                    $q2->whereNull('magister_id')->orWhere('magister_id', $magisterId);
                }))
                ->get()
                ->map(function ($event) {
                    $magister = $event->magister;
                    $room = $event->room;

                    return [
                        'id' => 'event-' . $event->id,
                        'title' => $event->title,
                        'description' => $event->description,
                        'start' => $event->start_time,
                        'end' => $event->end_time,
                        'room_id' => $event->room_id,
                        'room' => is_object($room) ? ['name' => $room->name] : null,
                        'editable' => false,
                        'backgroundColor' => is_object($magister) ? ($magister->color ?? '#a5f63b') : '#a5f63b',
                        'borderColor' => is_object($magister) ? ($magister->color ?? '#a5f63b') : '#a5f63b',
                        'type' => 'manual',
                        'magister' => is_object($magister) ? $magister->nombre : 'Sin magíster',
                        'modality' => null,
                    ];
                });

            \Log::debug('📦 Eventos manuales:', $manualEvents->toArray());
            \Log::debug('📦 Eventos de clases:', $classEvents->toArray());

           return response()->json(collect($manualEvents)->merge(collect($classEvents))->values());

        } catch (\Throwable $e) {
            \Log::error('🛑 Error en GuestEventController@index', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json(['error' => 'Error interno al procesar eventos.'], 500);
        }
    }
}

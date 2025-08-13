<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Event;


class GuestEventController extends Controller
{
    public function index(Request $request)
    {
        $magisterId = $request->get('magister_id');
        $roomId = $request->get('room_id');

        // Forzar a int si son numéricos
        $magisterId = is_numeric($magisterId) ? (int) $magisterId : null;
        $roomId = is_numeric($roomId) ? (int) $roomId : null;

        $classEvents = collect();
        $dias = [
            'Domingo' => 0,
            'Lunes' => 1,
            'Martes' => 2,
            'Miércoles' => 3,
            'Jueves' => 4,
            'Viernes' => 5,
            'Sábado' => 6,
        ];

        $clases = Clase::with(['room', 'period', 'course.magister'])
            ->when($magisterId, fn($q) => $q->whereHas('course', fn($qq) => $qq->where('magister_id', $magisterId)))
            ->when($roomId, fn($q) => $q->where('room_id', $roomId))
            ->get();

        foreach ($clases as $clase) {
            if (!$clase->period || !$clase->course || !$clase->hora_inicio || !$clase->hora_fin || !isset($dias[$clase->dia]))
                continue;

            $dayNumber = $dias[$clase->dia];
            $inicio = Carbon::parse($clase->period->fecha_inicio);
            $fin = Carbon::parse($clase->period->fecha_fin);

            $magister = $clase->course->magister;
            $color = $magister->color ?? '#6b7280';
            $modality = $clase->modality;
            $isOnline = $modality === 'online';
            $isHibrida = $modality === 'hibrida';

            while ($inicio->dayOfWeek !== $dayNumber)
                $inicio->addDay();

            while ($inicio->lte($fin)) {
                $start = $inicio->copy()->setTimeFromTimeString($clase->hora_inicio);
                $end = $inicio->copy()->setTimeFromTimeString($clase->hora_fin);
                $titulo = $clase->course->nombre . ($isOnline ? ' [ONLINE]' : ($isHibrida ? ' [HIBRIDA]' : ''));

                $descripcion = 'Magíster: ' . ($magister->nombre ?? 'Desconocido');
                if ($clase->url_zoom)
                    $descripcion .= "\n🔗 " . $clase->url_zoom;

                $classEvents->push([
                    'id' => 'clase-' . $clase->id . '-' . $start->format('Ymd'),
                    'title' => $titulo,
                    'description' => $descripcion,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'room_id' => $clase->room_id,
                    'room' => $clase->room ? ['name' => $clase->room->name] : null,
                    'editable' => false,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'type' => 'clase',
                    'magister' => $magister ? $magister->nombre : null,
                    'modality' => $modality,
                    'url_zoom' => $clase->url_zoom,
                ]);

                $inicio->addWeek();
            }
        }

        // Eventos manuales
        $manualEvents = Event::with(['room', 'magister'])
            ->when($magisterId, fn($q) => $q->where(function ($q2) use ($magisterId) {
                $q2->whereNull('magister_id')->orWhere('magister_id', $magisterId);
            }))
            ->when($roomId, fn($q) => $q->where('room_id', $roomId))
            ->get()
            ->map(function ($event) {
                $color = $event->magister->color ?? '#a5f63b';

                return [
                    'id' => 'event-' . $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'start' => $event->start_time,
                    'end' => $event->end_time,
                    'room_id' => $event->room_id,
                    'room' => $event->room ? ['name' => $event->room->name] : null,
                    'editable' => false,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'type' => 'manual',
                    'magister' => $event->magister ? $event->magister->nombre : null,
                    'modality' => null,
                ];
            });


        return response()->json($manualEvents->merge($classEvents)->values());
    }

}

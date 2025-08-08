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
        $coloresMagister = [
            'Economía' => '#3b82f6',
            'Dirección y Planificación Tributaria' => '#ef4444',
            'Gestión de Sistemas de Salud' => '#10b981',
            'Gestión y Políticas Públicas' => '#f97316',
        ];

        $filtroMagister = $request->get('magister');
        $filtroRoom = $request->get('room');

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
            ->when($filtroMagister, function ($query) use ($filtroMagister) {
                $query->whereHas('course.magister', function ($q) use ($filtroMagister) {
                    $q->where('nombre', $filtroMagister);
                });
            })
            ->when($filtroRoom, function ($query) use ($filtroRoom) {
                $query->whereHas('room', function ($q) use ($filtroRoom) {
                    $q->where('name', $filtroRoom);
                });
            })
            ->get();



        $clases->each(function ($clase) use (&$classEvents, $dias, $coloresMagister) {
            if (!$clase->period || !$clase->course || !$clase->hora_inicio || !$clase->hora_fin || !isset($dias[$clase->dia]))
                return;

            $dayNumber = $dias[$clase->dia];
            $fechaInicio = Carbon::parse($clase->period->fecha_inicio);
            $fechaFin = Carbon::parse($clase->period->fecha_fin);

            $magisterNombre = $clase->course->magister->nombre ?? 'Desconocido';
            $color = $coloresMagister[$magisterNombre] ?? '#6b7280';
            $modality = $clase->modality;
            $esOnline = $modality === 'online';
            $esHibrida = $modality === 'hibrida';

            while ($fechaInicio->dayOfWeek !== $dayNumber) {
                $fechaInicio->addDay();
            }

            while ($fechaInicio->lte($fechaFin)) {
                $start = $fechaInicio->copy()->setTimeFromTimeString($clase->hora_inicio);
                $end = $fechaInicio->copy()->setTimeFromTimeString($clase->hora_fin);

                $titulo = $clase->course->nombre;
                if ($esOnline)
                    $titulo .= ' [ONLINE]';
                elseif ($esHibrida)
                    $titulo .= ' [HIBRIDA]';

                $descripcion = 'Magíster: ' . $magisterNombre;
                if ($clase->url_zoom) {
                    $descripcion .= "\n🔗 " . $clase->url_zoom;
                }

                $classEvents->push([
                    'id' => 'clase-' . $clase->id . '-' . $start->format('Ymd'),
                    'title' => $titulo,
                    'description' => $descripcion,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'room_id' => $clase->room_id,
                    'room' => $clase->room ? ['name' => $clase->room->name] : null,
                    'editable' => false,
                    'backgroundColor' => $esOnline ? '#6366f1' : ($esHibrida ? '#facc15' : $color),
                    'borderColor' => $esOnline ? '#4338ca' : ($esHibrida ? '#eab308' : $color),
                    'type' => 'clase',
                    'magister' => $magisterNombre,
                    'modality' => $modality,
                    'url_zoom' => $clase->url_zoom,
                ]);

                $fechaInicio->addWeek();
            }
        });

        // Eventos creados manualmente (visibles para invitados)
        $manualEvents = Event::with('room')->get()->map(function ($event) {
            return [
                'id' => 'evento-' . $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start' => $event->start_time,
                'end' => $event->end_time,
                'room_id' => $event->room_id,
                'room' => $event->room ? ['name' => $event->room->name] : null,
                'editable' => false,
                'backgroundColor' => '#a5f63bff',
                'borderColor' => '#8add1eff',
                'type' => 'manual',
                'magister' => null,
                'modality' => null,
            ];
        })->values();


        return response()->json($manualEvents->merge($classEvents)->values());

    }
}

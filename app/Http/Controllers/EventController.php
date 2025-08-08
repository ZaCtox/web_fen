<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $coloresMagister = [
            'Economía' => '#3b82f6',
            'Dirección y Planificación Tributaria' => '#ef4444',
            'Gestión de Sistemas de Salud' => '#10b981',
            'Gestión y Políticas Públicas' => '#f97316',
        ];

        // Eventos manuales
        $manualEvents = Event::with('room')->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => Carbon::parse($event->start_time)->toIso8601String(),
                'end' => Carbon::parse($event->end_time)->toIso8601String(),
                'room_id' => $event->room_id,
                'room' => $event->room ? ['name' => $event->room->name] : null,
                'backgroundColor' => '#a5f63bff',
                'borderColor' => '#8add1eff',
                'editable' => Auth::check(),
                'type' => 'manual',
                'magister' => null,
                'modality' => null,
            ];
        })->values()->all();

        // Clases académicas
        $classEvents = collect();
        $dias = [
            'Domingo' => 0, 'Lunes' => 1, 'Martes' => 2,
            'Miércoles' => 3, 'Jueves' => 4, 'Viernes' => 5, 'Sábado' => 6,
        ];

        Clase::with(['room', 'period', 'course.magister'])->get()->each(function ($clase) use (&$classEvents, $dias, $coloresMagister) {
            if (!$clase->period || !$clase->course || !$clase->hora_inicio || !$clase->hora_fin || !isset($dias[$clase->dia])) return;

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
                if ($esOnline) $titulo .= ' [ONLINE]';
                elseif ($esHibrida) $titulo .= ' [HIBRIDA]';

                $descripcion = 'Magíster: ' . $magisterNombre;
                if ($clase->url_zoom) {
                    $descripcion .= "\n🔗 " . $clase->url_zoom;
                }

                $classEvents->push([
                    'id' => 'clase-' . $clase->id . '-' . $start->format('Ymd'),
                    'title' => $titulo,
                    'description' => $descripcion,
                    'start' => $start->toIso8601String(),
                    'end' => $end->toIso8601String(),
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

        return response()->json(array_merge($manualEvents, $classEvents->values()->all()));
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        $validated['start_time'] = Carbon::parse($validated['start_time'])->format('Y-m-d H:i:s');
        $validated['end_time'] = Carbon::parse($validated['end_time'])->format('Y-m-d H:i:s');
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'activo';
        $validated['type'] = 'manual';

        $event = Event::create($validated);

        // Devolver formato compatible con FullCalendar
        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'start' => Carbon::parse($event->start_time)->toIso8601String(),
            'end' => Carbon::parse($event->end_time)->toIso8601String(),
            'room_id' => $event->room_id,
            'room' => $event->room ? ['name' => $event->room->name] : null,
            'backgroundColor' => '#a5f63bff',
            'borderColor' => '#8add1eff',
            'editable' => true,
            'type' => 'manual',
        ]);
    }

    // update() y destroy() pueden mantenerse igual por ahora
}

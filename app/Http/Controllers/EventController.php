<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Room;
use App\Models\RoomUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        // Eventos manuales creados por usuarios
        $manualEvents = Event::with('room')->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_time,
                'end' => $event->end_time,
                'room_id' => $event->room_id,
                'room' => $event->room ? ['name' => $event->room->name] : null,
                'backgroundColor' => '#3b82f6',
                'borderColor' => '#3b82f6',
                'editable' => true,
            ];
        });

        // Eventos automáticos por uso de sala
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

        RoomUsage::with(['room', 'trimestre'])->get()->each(function ($uso) use (&$classEvents, $dias) {
            if (
                !$uso->trimestre ||
                !$uso->hora_inicio ||
                !$uso->hora_fin ||
                !isset($dias[$uso->dia])
            )
                return;

            $dayNumber = $dias[$uso->dia];
            $fechaInicio = \Carbon\Carbon::parse($uso->trimestre->fecha_inicio);
            $fechaFin = \Carbon\Carbon::parse($uso->trimestre->fecha_fin);

            // Mover fechaInicio hasta el primer día coincidente
            while ($fechaInicio->dayOfWeek !== $dayNumber) {
                $fechaInicio->addDay();
            }

            while ($fechaInicio->lte($fechaFin)) {
                $start = $fechaInicio->copy()->setTimeFromTimeString($uso->hora_inicio);
                $end = $fechaInicio->copy()->setTimeFromTimeString($uso->hora_fin);

                $classEvents->push([
                    'id' => 'roomusage-' . $uso->id . '-' . $start->format('Ymd'),
                    'title' => $uso->subject ?? 'Clase',
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'room_id' => $uso->room_id,
                    'room' => $uso->room ? ['name' => $uso->room->name] : null,
                    'editable' => false,
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#10b981',
                ]);

                $fechaInicio->addWeek();
            }
        });

        // Unimos eventos manuales y de uso de sala
        return response()->json(
            $manualEvents->toBase()->merge($classEvents->toBase())->values()
        );
    }



    public function store(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'room_id' => 'nullable|exists:rooms,id',
            'type' => 'nullable|string|max:255',
        ]);

        $validated['start_time'] = Carbon::parse($validated['start_time'])->format('Y-m-d H:i:s');
        $validated['end_time'] = Carbon::parse($validated['end_time'])->format('Y-m-d H:i:s');
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'activo';

        $event = Event::create($validated);

        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

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
            $validated['start_time'] = Carbon::parse($validated['start_time'])->format('Y-m-d H:i:s');
        }

        if (isset($validated['end_time'])) {
            $validated['end_time'] = Carbon::parse($validated['end_time'])->format('Y-m-d H:i:s');
        }

        $event->update($validated);

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $event->delete();
        return response()->json(['message' => 'Evento eliminado']);
    }
}

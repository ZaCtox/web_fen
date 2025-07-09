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

        $classEvents = RoomUsage::with('room')->get()->map(function ($uso, $index) {
            $dias = ['Viernes' => 4, 'Sábado' => 5];
            if (!isset($dias[$uso->dia]) || !$uso->horario) return null;

            [$horaInicio, $horaFin] = explode('-', $uso->horario);
            $start = now()->startOfWeek()->addDays($dias[$uso->dia])->setTimeFromTimeString(trim($horaInicio));
            $end = now()->startOfWeek()->addDays($dias[$uso->dia])->setTimeFromTimeString(trim($horaFin));

            return [
                'id' => 'roomusage-' . $index,
                'title' => $uso->subject,
                'start' => $start->toDateTimeString(),
                'end' => $end->toDateTimeString(),
                'room_id' => $uso->room_id,
                'room' => $uso->room ? ['name' => $uso->room->name] : null,
                'editable' => false,
                'backgroundColor' => '#10b981',
                'borderColor' => '#10b981',
            ];
        })->filter();

        return response()->json($manualEvents->toBase()->merge($classEvents->toBase())->values());
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

<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUsage;
use App\Models\Period;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoomRequest;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $ubicacion = $request->input('ubicacion');
        $capacidad = $request->input('capacidad');

        $rooms = Room::query()
            ->when($ubicacion, fn($q) => $q->where('location', 'like', "%$ubicacion%"))
            ->when($capacidad, fn($q) => $q->where('capacity', '>=', $capacidad))
            ->orderBy('name')
            ->paginate(10);

        return view('rooms.index', compact('rooms', 'ubicacion', 'capacidad'));
    }


    public function create()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();
        $cursos = Course::with('magister')->orderBy('magister_id')->orderBy('nombre')->get();

        return view('rooms.create', compact('periodos', 'cursos'));
    }


    public function store(StoreRoomRequest $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $room = Room::create($request->validated());

        return redirect()->route('rooms.asignar', $room)->with('success', 'Sala creada. Ahora asigna sus usos.');
    }

    public function edit(Room $room)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('rooms.edit', compact('room'));
    }

    public function update(StoreRoomRequest $request, Room $room)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $room->update($request->validated());
        return redirect()->route('rooms.index')->with('success', 'Sala actualizada correctamente');
    }

    public function destroy(Room $room)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Sala eliminada');
    }

    public function show(Request $request, Room $room)
    {
        $usosQuery = $room->usages()->with(['period', 'course.magister']);

        if ($request->filled('period_id')) {
            $usosQuery->where('period_id', $request->period_id);
        }

        if ($request->filled('magister_id')) {
            $usosQuery->whereHas(
                'course',
                fn($q) =>
                $q->where('magister_id', $request->magister_id)
            );
        }

        $usos = $usosQuery->orderBy('period_id')->get();
        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();

        return view('rooms.show', compact('room', 'usos', 'periodos'));
    }


    public function asignarUso(Room $room)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();
        $cursos = Course::with('magister')->orderBy('magister_id')->orderBy('nombre')->get();

        return view('rooms.asignar', compact('room', 'periodos', 'cursos'));
    }

    public function guardarUso(Request $request, Room $room)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $request->validate([
            'usos' => 'required|array',
            'usos.*.period_id' => 'required|exists:periods,id',
            'usos.*.course_id' => 'required|exists:courses,id',
            'usos.*.dia' => 'required|string',
            'usos.*.hora_inicio' => 'required',
            'usos.*.hora_fin' => 'required',
        ]);

        foreach ($request->input('usos', []) as $uso) {
            $room->usages()->create([
                'period_id' => $uso['period_id'],
                'course_id' => $uso['course_id'],
                'dia' => $uso['dia'],
                'hora_inicio' => $uso['hora_inicio'],
                'hora_fin' => $uso['hora_fin'],
            ]);
        }

        return redirect()->route('rooms.index', $room)->with('success', 'Usos académicos asignados.');
    }
}

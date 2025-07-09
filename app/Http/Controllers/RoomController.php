<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUsage;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoomRequest;

class RoomController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('rooms.create');
    }

    public function store(StoreRoomRequest $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $room = Room::create($request->validated());

        foreach ($request->input('usos', []) as $uso) {
            $room->usages()->create($uso);
        }

        return redirect()->route('rooms.index')->with('success', 'Sala creada correctamente');
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
        $room->usages()->delete();

        foreach ($request->input('usos', []) as $uso) {
            $room->usages()->create($uso);
        }

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
        $usosQuery = $room->usages();

        if ($request->filled('year')) {
            $usosQuery->where('year', $request->year);
        }

        if ($request->filled('trimestre')) {
            $usosQuery->where('trimestre', $request->trimestre);
        }

        $usos = $usosQuery->orderBy('year')->orderBy('trimestre')->get();

        return view('rooms.show', compact('room', 'usos'));
    }

}

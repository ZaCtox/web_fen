<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUsage;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoomRequest;
use Illuminate\Support\Collection;

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

        $trimestres = Trimestre::orderBy('año')->orderBy('numero')->get();
        return view('rooms.create', compact('trimestres'));
    }

    public function store(StoreRoomRequest $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $room = Room::create($request->validated());

        foreach ($request->input('usos', []) as $uso) {
            $room->usages()->create([
                'trimestre_id' => $uso['trimestre_id'],
                'dia' => $uso['dia'],
                'hora_inicio' => $uso['hora_inicio'],
                'hora_fin' => $uso['hora_fin'],
                'magister' => $uso['magister'],
                'subject' => $uso['subject'],
            ]);
        }

        return redirect()->route('rooms.index')->with('success', 'Sala creada correctamente');
    }

    public function edit(Room $room)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $trimestres = Trimestre::orderBy('año')->orderBy('numero')->get();
        return view('rooms.edit', compact('room', 'trimestres'));
    }

    public function update(StoreRoomRequest $request, Room $room)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $room->update($request->validated());
        $room->usages()->delete();

        foreach ($request->input('usos', []) as $uso) {
            $room->usages()->create([
                'trimestre_id' => $uso['trimestre_id'],
                'dia' => $uso['dia'],
                'hora_inicio' => $uso['hora_inicio'],
                'hora_fin' => $uso['hora_fin'],
            ]);
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

        if ($request->filled('trimestre_id')) {
            $usosQuery->where('trimestre_id', $request->trimestre_id);
        }

        $usos = $usosQuery->with('trimestre')->orderBy('trimestre_id')->get();
        $trimestres = Trimestre::orderBy('año')->orderBy('numero')->get();

        return view('rooms.show', compact('room', 'usos', 'trimestres'));
    }
}

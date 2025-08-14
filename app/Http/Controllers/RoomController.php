<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUsage;
use App\Models\Period;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoomRequest;
use App\Models\Magister;

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

        $data = $request->validated();

        foreach ($this->booleanFields() as $campo) {
            $data[$campo] = $request->has($campo);
        }

        $room = Room::create($data);

        return redirect()->route('rooms.index', $room)->with('success', 'Sala creada. Ahora asigna sus usos.');
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

        $data = $request->validated();

        foreach ($this->booleanFields() as $campo) {
            $data[$campo] = $request->has($campo);
        }

        $room->update($data);

        return redirect()->route('rooms.index')->with('success', 'Sala actualizada correctamente');
    }

    private function booleanFields()
    {
        return [
            'calefaccion',
            'energia_electrica',
            'existe_aseo',
            'plumones',
            'borrador',
            'pizarra_limpia',
            'computador_funcional',
            'cables_computador',
            'control_remoto_camara',
            'televisor_funcional',
        ];
    }

    public function destroy(Room $room)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Sala eliminada');
    }

public function show(Room $room)
{
    $clases = $room->clases()->with(['course.magister', 'period'])->get();

    // Para filtros dinámicos
    $magisters = Magister::orderBy('nombre')->get();
    $dias = ['Viernes', 'Sábado'];
    $trimestres = Period::orderBy('anio')->orderBy('numero')->get();

    return view('rooms.show', compact('room', 'clases', 'magisters', 'dias', 'trimestres'));
}




}

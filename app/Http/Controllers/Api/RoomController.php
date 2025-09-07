<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Listar todas las salas con filtros
    public function index(Request $request)
    {
        $ubicacion = $request->input('ubicacion');
        $capacidad = $request->input('capacidad');

        $rooms = Room::query()
            ->when($ubicacion, fn($q) => $q->where('location', 'like', "%$ubicacion%"))
            ->when($capacidad, fn($q) => $q->where('capacity', '>=', $capacidad))
            ->orderBy('name')
            ->paginate(10);

        return response()->json($rooms);
    }

    // Mostrar una sala con sus clases relacionadas
    public function show($id)
    {
        $room = Room::with(['clases.course.magister', 'clases.period'])->find($id);

        if (!$room) {
            return response()->json(['message' => 'Sala no encontrada'], 404);
        }

        return response()->json($room);
    }

    // Crear sala
    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();

        foreach ($this->booleanFields() as $campo) {
            $data[$campo] = $request->has($campo);
        }

        $room = Room::create($data);

        return response()->json([
            'message' => 'Sala creada correctamente',
            'data' => $room
        ], 201);
    }

    // Actualizar sala
    public function update(StoreRoomRequest $request, $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['message' => 'Sala no encontrada'], 404);
        }

        $data = $request->validated();

        foreach ($this->booleanFields() as $campo) {
            $data[$campo] = $request->has($campo);
        }

        $room->update($data);

        return response()->json([
            'message' => 'Sala actualizada correctamente',
            'data' => $room
        ]);
    }

    // Eliminar sala
    public function destroy($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['message' => 'Sala no encontrada'], 404);
        }

        $room->delete();

        return response()->json(['message' => 'Sala eliminada correctamente']);
    }

    // Campos booleanos
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
}

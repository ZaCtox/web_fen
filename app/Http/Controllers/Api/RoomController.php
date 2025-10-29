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
        $search = $request->input('search');

        $query = Room::query()
            ->when($ubicacion, fn($q) => $q->where('location', 'like', "%$ubicacion%"))
            ->when($capacidad, fn($q) => $q->where('capacity', '>=', $capacidad))
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"));

        // Ordenamiento
        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        $rooms = $query->orderBy($sortBy, $sortDirection)
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
            $data[$campo] = $request->input($campo, 0) ? 1 : 0; // ✅ SOLUCIÓN
        }

        $room = Room::create($data);

        return response()->json([
            'message' => 'Sala creada correctamente',
            'data' => $room,
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
            $data[$campo] = $request->input($campo, 0) ? 1 : 0; // ✅ SOLUCIÓN
        }

        $room->update($data);

        return response()->json([
            'message' => 'Sala actualizada correctamente',
            'data' => $room,
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

    // ===== MÉTODO PÚBLICO (SIN AUTENTICACIÓN) =====
    public function publicIndex(Request $request)
    {
        try {
            // Obtener salas para vista pública
            $rooms = Room::select(
                'id',
                'name',
                'capacity',
                'location',
                'calefaccion',
                'energia_electrica',
                'existe_aseo',
                'plumones',
                'borrador',
                'pizarra_limpia',
                'computador_funcional',
                'cables_computador',
                'control_remoto_camara',
                'televisor_funcional'
            )
                ->orderBy('name')
                ->get();

            // Formatear datos para respuesta pública
            $formattedRooms = $rooms->map(function ($room) {
                // Crear lista de equipamiento disponible
                $equipmentList = [];

                if ($room->calefaccion)
                    $equipmentList[] = 'Calefacción';
                if ($room->energia_electrica)
                    $equipmentList[] = 'Energía Eléctrica';
                if ($room->existe_aseo)
                    $equipmentList[] = 'Aseo';
                if ($room->plumones)
                    $equipmentList[] = 'Plumones';
                if ($room->borrador)
                    $equipmentList[] = 'Borrador';
                if ($room->pizarra_limpia)
                    $equipmentList[] = 'Pizarra';
                if ($room->computador_funcional)
                    $equipmentList[] = 'Computador';
                if ($room->cables_computador)
                    $equipmentList[] = 'Cables';
                if ($room->control_remoto_camara)
                    $equipmentList[] = 'Control Remoto';
                if ($room->televisor_funcional)
                    $equipmentList[] = 'Televisor';

                $equipmentString = !empty($equipmentList) ? implode(', ', $equipmentList) : 'Equipamiento básico';

                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'capacity' => $room->capacity,
                    'location' => $room->location,
                    'equipment' => $equipmentString,
                    'status' => 'Disponible', // ← CAMBIAR: 'available' → 'Disponible'
                    'public_view' => true
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $formattedRooms,
                'meta' => [
                    'total' => $formattedRooms->count(),
                    'public_view' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cargar las salas: ' . $e->getMessage()
            ], 500);
        }
    }
}


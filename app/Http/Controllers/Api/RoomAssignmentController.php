<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomAssignment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="RoomAssignments",
 *     description="API Endpoints para gestión de asignaciones de salas"
 * )
 */
class RoomAssignmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/room-assignments",
     *     summary="Listar todas las asignaciones de salas",
     *     tags={"RoomAssignments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="room_id",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="academic_year",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="trimester",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de asignaciones de salas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/RoomAssignment")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = RoomAssignment::with(['room', 'subject.program']);

        if ($request->has('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->has('trimester')) {
            $query->where('trimester', $request->trimester);
        }

        $assignments = $query->active()->get();

        return response()->json([
            'success' => true,
            'data' => $assignments
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/room-assignments",
     *     summary="Crear una nueva asignación de sala",
     *     tags={"RoomAssignments"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RoomAssignmentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Asignación de sala creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RoomAssignment")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'subject_id' => 'required|exists:subjects,id',
                'academic_year' => 'required|integer|min:2020|max:2030',
                'trimester' => 'required|integer|min:1|max:4',
                'schedule' => 'required|string|max:255',
            ]);

            // Verificar que no haya conflicto de horario
            $conflict = RoomAssignment::where('room_id', $validated['room_id'])
                ->where('academic_year', $validated['academic_year'])
                ->where('trimester', $validated['trimester'])
                ->where('schedule', $validated['schedule'])
                ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una asignación para esta sala en el mismo horario, año y trimestre'
                ], 400);
            }

            $assignment = RoomAssignment::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Asignación de sala creada exitosamente',
                'data' => $assignment->load(['room', 'subject.program'])
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/room-assignments/{id}",
     *     summary="Obtener una asignación de sala específica",
     *     tags={"RoomAssignments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación de sala encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/RoomAssignment")
     *     )
     * )
     */
    public function show(RoomAssignment $roomAssignment): JsonResponse
    {
        $roomAssignment->load(['room', 'subject.program']);
        
        return response()->json([
            'success' => true,
            'data' => $roomAssignment
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/room-assignments/{id}",
     *     summary="Actualizar una asignación de sala",
     *     tags={"RoomAssignments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RoomAssignmentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación de sala actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/RoomAssignment")
     *     )
     * )
     */
    public function update(Request $request, RoomAssignment $roomAssignment): JsonResponse
    {
        try {
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'subject_id' => 'required|exists:subjects,id',
                'academic_year' => 'required|integer|min:2020|max:2030',
                'trimester' => 'required|integer|min:1|max:4',
                'schedule' => 'required|string|max:255',
                'is_active' => 'boolean',
            ]);

            // Verificar que no haya conflicto de horario (excluyendo la asignación actual)
            $conflict = RoomAssignment::where('room_id', $validated['room_id'])
                ->where('academic_year', $validated['academic_year'])
                ->where('trimester', $validated['trimester'])
                ->where('schedule', $validated['schedule'])
                ->where('id', '!=', $roomAssignment->id)
                ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una asignación para esta sala en el mismo horario, año y trimestre'
                ], 400);
            }

            $roomAssignment->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Asignación de sala actualizada exitosamente',
                'data' => $roomAssignment->load(['room', 'subject.program'])
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/room-assignments/{id}",
     *     summary="Eliminar una asignación de sala",
     *     tags={"RoomAssignments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación de sala eliminada exitosamente"
     *     )
     * )
     */
    public function destroy(RoomAssignment $roomAssignment): JsonResponse
    {
        $roomAssignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asignación de sala eliminada exitosamente'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Subjects",
 *     description="API Endpoints para gestión de asignaturas"
 * )
 */
class SubjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/subjects",
     *     summary="Listar todas las asignaturas",
     *     tags={"Subjects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="program_id",
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
     *         description="Lista de asignaturas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Subject")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Subject::with(['program', 'roomAssignments']);

        if ($request->has('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->has('trimester')) {
            $query->where('trimester', $request->trimester);
        }

        $subjects = $query->active()->get();

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/subjects",
     *     summary="Crear una nueva asignatura",
     *     tags={"Subjects"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SubjectRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Asignatura creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Subject")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'credits' => 'required|integer|min:1|max:20',
                'program_id' => 'required|exists:programs,id',
                'trimester' => 'required|integer|min:1|max:4',
                'academic_year' => 'required|integer|min:2020|max:2030',
            ]);

            $subject = Subject::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Asignatura creada exitosamente',
                'data' => $subject->load('program')
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
     *     path="/api/subjects/{id}",
     *     summary="Obtener una asignatura específica",
     *     tags={"Subjects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignatura encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Subject")
     *     )
     * )
     */
    public function show(Subject $subject): JsonResponse
    {
        $subject->load(['program', 'roomAssignments.room']);
        
        return response()->json([
            'success' => true,
            'data' => $subject
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/subjects/{id}",
     *     summary="Actualizar una asignatura",
     *     tags={"Subjects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SubjectRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignatura actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Subject")
     *     )
     * )
     */
    public function update(Request $request, Subject $subject): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'credits' => 'required|integer|min:1|max:20',
                'program_id' => 'required|exists:programs,id',
                'trimester' => 'required|integer|min:1|max:4',
                'academic_year' => 'required|integer|min:2020|max:2030',
                'is_active' => 'boolean',
            ]);

            $subject->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Asignatura actualizada exitosamente',
                'data' => $subject->load('program')
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
     *     path="/api/subjects/{id}",
     *     summary="Eliminar una asignatura",
     *     tags={"Subjects"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignatura eliminada exitosamente"
     *     )
     * )
     */
    public function destroy(Subject $subject): JsonResponse
    {
        // Verificar si tiene asignaciones de sala
        if ($subject->roomAssignments()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la asignatura porque tiene asignaciones de sala'
            ], 400);
        }

        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Asignatura eliminada exitosamente'
        ]);
    }
}

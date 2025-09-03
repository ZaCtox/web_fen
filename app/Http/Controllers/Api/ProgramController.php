<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Programs",
 *     description="API Endpoints para gestión de programas de magíster"
 * )
 */
class ProgramController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/programs",
     *     summary="Listar todos los programas",
     *     tags={"Programs"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de programas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Program")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $programs = Program::active()->with('subjects')->get();
        
        return response()->json([
            'success' => true,
            'data' => $programs
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/programs",
     *     summary="Crear un nuevo programa",
     *     tags={"Programs"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProgramRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Programa creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Program")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:programs',
                'description' => 'nullable|string',
                'duration_trimesters' => 'required|integer|min:1|max:12',
            ]);

            $program = Program::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Programa creado exitosamente',
                'data' => $program
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
     *     path="/api/programs/{id}",
     *     summary="Obtener un programa específico",
     *     tags={"Programs"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Programa encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Program")
     *     )
     * )
     */
    public function show(Program $program): JsonResponse
    {
        $program->load('subjects');
        
        return response()->json([
            'success' => true,
            'data' => $program
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/programs/{id}",
     *     summary="Actualizar un programa",
     *     tags={"Programs"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProgramRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Programa actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Program")
     *     )
     * )
     */
    public function update(Request $request, Program $program): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:programs,name,' . $program->id,
                'description' => 'nullable|string',
                'duration_trimesters' => 'required|integer|min:1|max:12',
                'is_active' => 'boolean',
            ]);

            $program->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Programa actualizado exitosamente',
                'data' => $program
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
     *     path="/api/programs/{id}",
     *     summary="Eliminar un programa",
     *     tags={"Programs"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Programa eliminado exitosamente"
     *     )
     * )
     */
    public function destroy(Program $program): JsonResponse
    {
        // Verificar si tiene asignaturas asociadas
        if ($program->subjects()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el programa porque tiene asignaturas asociadas'
            ], 400);
        }

        $program->delete();

        return response()->json([
            'success' => true,
            'message' => 'Programa eliminado exitosamente'
        ]);
    }
}

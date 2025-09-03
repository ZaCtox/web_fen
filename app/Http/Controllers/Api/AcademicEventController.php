<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="AcademicEvents",
 *     description="API Endpoints para gestión de eventos académicos"
 * )
 */
class AcademicEventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/academic-events",
     *     summary="Listar todos los eventos académicos",
     *     tags={"AcademicEvents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="event_type",
     *         in="query",
     *         @OA\Schema(type="string", enum={"clase", "examen", "reunion", "evento_especial", "otro"})
     *     ),
     *     @OA\Parameter(
     *         name="room_id",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="magister_id",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="period_id",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de eventos académicos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AcademicEvent")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = AcademicEvent::with(['room', 'magister', 'period']);

        if ($request->has('event_type')) {
            $query->byType($request->event_type);
        }

        if ($request->has('room_id')) {
            $query->byRoom($request->room_id);
        }

        if ($request->has('magister_id')) {
            $query->byMagister($request->magister_id);
        }

        if ($request->has('period_id')) {
            $query->byPeriod($request->period_id);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        $events = $query->orderBy('start_date')->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/academic-events",
     *     summary="Crear un nuevo evento académico",
     *     tags={"AcademicEvents"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AcademicEventRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Evento académico creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/AcademicEvent")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'room_id' => 'nullable|exists:rooms,id',
                'magister_id' => 'nullable|exists:magisters,id',
                'period_id' => 'nullable|exists:periods,id',
                'event_type' => 'required|in:clase,examen,reunion,evento_especial,otro',
                'color' => 'nullable|string|max:7',
                'is_all_day' => 'boolean',
            ]);

            $event = AcademicEvent::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Evento académico creado exitosamente',
                'data' => $event->load(['room', 'magister', 'period'])
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
     *     path="/api/academic-events/{id}",
     *     summary="Obtener un evento académico específico",
     *     tags={"AcademicEvents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evento académico encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/AcademicEvent")
     *     )
     * )
     */
    public function show(AcademicEvent $academicEvent): JsonResponse
    {
        $academicEvent->load(['room', 'magister', 'period']);
        
        return response()->json([
            'success' => true,
            'data' => $academicEvent
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/academic-events/{id}",
     *     summary="Actualizar un evento académico",
     *     tags={"AcademicEvents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AcademicEventRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evento académico actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/AcademicEvent")
     *     )
     * )
     */
    public function update(Request $request, AcademicEvent $academicEvent): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'room_id' => 'nullable|exists:rooms,id',
                'magister_id' => 'nullable|exists:magisters,id',
                'period_id' => 'nullable|exists:periods,id',
                'event_type' => 'required|in:clase,examen,reunion,evento_especial,otro',
                'color' => 'nullable|string|max:7',
                'is_all_day' => 'boolean',
            ]);

            $academicEvent->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Evento académico actualizado exitosamente',
                'data' => $academicEvent->load(['room', 'magister', 'period'])
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
     *     path="/api/academic-events/{id}",
     *     summary="Eliminar un evento académico",
     *     tags={"AcademicEvents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evento académico eliminado exitosamente"
     *     )
     * )
     */
    public function destroy(AcademicEvent $academicEvent): JsonResponse
    {
        $academicEvent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evento académico eliminado exitosamente'
        ]);
    }
}

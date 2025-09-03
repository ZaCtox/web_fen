<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\IncidentImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * @OA\Tag(
 *     name="Incidents",
 *     description="API Endpoints para gestión de incidencias"
 * )
 */
class IncidentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/incidents",
     *     summary="Listar todas las incidencias",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="estado",
     *         in="query",
     *         @OA\Schema(type="string", enum={"pendiente", "en_progreso", "resuelta"})
     *     ),
     *     @OA\Parameter(
     *         name="room_id",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de incidencias",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Incident")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Incident::with(['user', 'room', 'resolvedBy', 'images']);

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $incidents = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $incidents
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/incidents",
     *     summary="Crear una nueva incidencia",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/IncidentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Incidencia creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Incident")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'room_id' => 'required|exists:rooms,id',
                'estado' => 'required|in:pendiente,en_progreso,resuelta',
                'comentario' => 'nullable|string',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $validated['user_id'] = $request->user()->id;
            $validated['nro_ticket'] = 'INC-' . date('Y') . '-' . str_pad(Incident::count() + 1, 4, '0', STR_PAD_LEFT);

            $incident = Incident::create($validated);

            // Procesar imágenes si se proporcionan
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $cloudinaryResponse = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'incidents',
                        'public_id' => 'incident_' . $incident->id . '_' . uniqid(),
                    ]);

                    IncidentImage::create([
                        'incident_id' => $incident->id,
                        'image_url' => $cloudinaryResponse->getSecurePath(),
                        'cloudinary_public_id' => $cloudinaryResponse->getPublicId(),
                        'alt_text' => 'Imagen de incidencia: ' . $incident->titulo,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Incidencia creada exitosamente',
                'data' => $incident->load(['user', 'room', 'images'])
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
     *     path="/api/incidents/{id}",
     *     summary="Obtener una incidencia específica",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incidencia encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Incident")
     *     )
     * )
     */
    public function show(Incident $incident): JsonResponse
    {
        $incident->load(['user', 'room', 'resolvedBy', 'images', 'logs']);
        
        return response()->json([
            'success' => true,
            'data' => $incident
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/incidents/{id}",
     *     summary="Actualizar una incidencia",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/IncidentUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incidencia actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Incident")
     *     )
     * )
     */
    public function update(Request $request, Incident $incident): JsonResponse
    {
        try {
            $validated = $request->validate([
                'titulo' => 'sometimes|required|string|max:255',
                'descripcion' => 'sometimes|required|string',
                'estado' => 'sometimes|required|in:pendiente,en_progreso,resuelta',
                'comentario' => 'nullable|string',
                'resolved_by' => 'nullable|exists:users,id',
            ]);

            // Si se está resolviendo la incidencia
            if (isset($validated['estado']) && $validated['estado'] === 'resuelta') {
                $validated['resuelta_en'] = now();
                $validated['resolved_by'] = $request->user()->id;
            }

            $incident->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Incidencia actualizada exitosamente',
                'data' => $incident->load(['user', 'room', 'resolvedBy', 'images'])
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
     *     path="/api/incidents/{id}",
     *     summary="Eliminar una incidencia",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incidencia eliminada exitosamente"
     *     )
     * )
     */
    public function destroy(Incident $incident): JsonResponse
    {
        // Eliminar imágenes de Cloudinary
        foreach ($incident->images as $image) {
            if ($image->cloudinary_public_id) {
                Cloudinary::destroy($image->cloudinary_public_id);
            }
        }

        $incident->delete();

        return response()->json([
            'success' => true,
            'message' => 'Incidencia eliminada exitosamente'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/incidents/{id}/images",
     *     summary="Agregar imágenes a una incidencia",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="images", type="array", @OA\Items(type="string", format="binary"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Imágenes agregadas exitosamente"
     *     )
     * )
     */
    public function addImages(Request $request, Incident $incident): JsonResponse
    {
        try {
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $uploadedImages = [];

            foreach ($request->file('images') as $image) {
                $cloudinaryResponse = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'incidents',
                    'public_id' => 'incident_' . $incident->id . '_' . uniqid(),
                ]);

                $incidentImage = IncidentImage::create([
                    'incident_id' => $incident->id,
                    'image_url' => $cloudinaryResponse->getSecurePath(),
                    'cloudinary_public_id' => $cloudinaryResponse->getPublicId(),
                    'alt_text' => 'Imagen de incidencia: ' . $incident->titulo,
                ]);

                $uploadedImages[] = $incidentImage;
            }

            return response()->json([
                'success' => true,
                'message' => 'Imágenes agregadas exitosamente',
                'data' => $uploadedImages
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
     * @OA\Get(
     *     path="/api/incidents/report/pdf",
     *     summary="Generar reporte PDF de incidencias",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="estado",
     *         in="query",
     *         @OA\Schema(type="string", enum={"pendiente", "en_progreso", "resuelta"})
     *     ),
     *     @OA\Parameter(
     *         name="room_id",
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
     *         description="Reporte PDF generado exitosamente",
     *         @OA\MediaType(mediaType="application/pdf")
     *     )
     * )
     */
    public function generatePdfReport(Request $request)
    {
        $query = Incident::with(['user', 'room']);

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $incidents = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('reports.incidents', [
            'incidents' => $incidents,
            'filters' => $request->all(),
            'generated_at' => now()->format('d/m/Y H:i:s')
        ]);

        return $pdf->download('reporte-incidencias-' . now()->format('Y-m-d') . '.pdf');
    }
}

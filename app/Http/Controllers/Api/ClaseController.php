<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Course;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    /**
     * Listar todas las clases (con curso, magíster, período y sala).
     */
    public function index(Request $request)
    {
        $filters = $request->only('magister', 'sala', 'dia');

        $clases = Clase::with(['course.magister', 'period', 'room'])
            ->filtrar($filters)
            ->orderBy('period_id')
            ->orderByRaw("FIELD(dia, 'Viernes','Sábado')")
            ->orderBy('hora_inicio')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $clases
        ]);
    }

    /**
     * Crear nueva clase.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'tipo' => 'required|string|max:50',
            'period_id' => 'required|exists:periods,id',
            'room_id' => 'nullable|exists:rooms,id',
            'modality' => 'required|string|in:online,presencial,hibrida',
            'dia' => 'required|in:Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'url_zoom' => 'nullable|url',
            'encargado' => 'nullable|string|max:255'
        ]);

        // Validar que url_zoom sea requerido en online e híbrida
        if (($validated['modality'] === 'online' || $validated['modality'] === 'hibrida') && empty($validated['url_zoom'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'El enlace de Zoom es obligatorio para clases Online e Híbridas.',
                'errors' => [
                    'url_zoom' => ['El enlace de Zoom es obligatorio para clases Online e Híbridas.']
                ]
            ], 422);
        }

        $clase = Clase::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Clase creada correctamente.',
            'data' => $clase->load(['course.magister', 'period', 'room'])
        ], 201);
    }

    /**
     * Mostrar una clase específica.
     */
    public function show(Clase $clase)
    {
        return response()->json([
            'status' => 'success',
            'data' => $clase->load(['course.magister', 'period', 'room'])
        ]);
    }

    /**
     * Actualizar clase.
     */
    public function update(Request $request, Clase $clase)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'tipo' => 'required|string|max:50',
            'period_id' => 'required|exists:periods,id',
            'room_id' => 'nullable|exists:rooms,id',
            'modality' => 'required|string|in:online,presencial,hibrida',
            'dia' => 'required|in:Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'url_zoom' => 'nullable|url',
            'encargado' => 'nullable|string|max:255'
        ]);

        // Validar que url_zoom sea requerido en online e híbrida
        if (($validated['modality'] === 'online' || $validated['modality'] === 'hibrida') && empty($validated['url_zoom'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'El enlace de Zoom es obligatorio para clases Online e Híbridas.',
                'errors' => [
                    'url_zoom' => ['El enlace de Zoom es obligatorio para clases Online e Híbridas.']
                ]
            ], 422);
        }

        $clase->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Clase actualizada correctamente.',
            'data' => $clase->load(['course.magister', 'period', 'room'])
        ]);
    }

    /**
     * Eliminar clase.
     */
    public function destroy(Clase $clase)
    {
        $clase->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Clase eliminada correctamente.'
        ]);
    }

    /**
     * Listar clases optimizado para grandes volúmenes de datos
     */
    /**
     * Listar clases optimizado para grandes volúmenes de datos
     */
    public function simple(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 30); // Reducir de 50 a 30 para evitar JSON incompleto

            // Usar paginación simple con offset/limit en lugar de paginate()
            $clases = Clase::select([
                'id',
                'course_id',
                'tipo',
                'period_id',
                'room_id',
                'modality',
                'dia',
                'hora_inicio',
                'hora_fin',
                'url_zoom',
                'encargado',
                'created_at',
                'updated_at'
            ])
                ->with([
                    'course:id,nombre,magister_id',
                    'period:id,numero,anio',
                    'room:id,name'
                ])
                ->orderBy('period_id')
                ->orderByRaw("FIELD(dia, 'Viernes','Sábado')")
                ->orderBy('hora_inicio')
                ->offset(($page - 1) * $perPage)
                ->limit($perPage)
                ->get();

            // Contar total de clases
            $total = Clase::count();
            $lastPage = ceil($total / $perPage);

            // Cargar magísteres de forma separada para evitar N+1
            $courseIds = $clases->pluck('course_id')->unique()->filter();
            $coursesWithMagisters = Course::select('id', 'nombre', 'magister_id')
                ->with('magister:id,nombre,color')
                ->whereIn('id', $courseIds)
                ->get()
                ->keyBy('id');

            // Asignar cursos con magísteres
            foreach ($clases as $clase) {
                if ($clase->course_id && isset($coursesWithMagisters[$clase->course_id])) {
                    $clase->course = $coursesWithMagisters[$clase->course_id];
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $clases, // Datos directos sin paginate()
                'pagination' => [
                    'current_page' => (int) $page,
                    'per_page' => (int) $perPage,
                    'total' => (int) $total,
                    'last_page' => (int) $lastPage,
                    'has_more_pages' => $page < $lastPage,
                    'from' => (($page - 1) * $perPage) + 1,
                    'to' => min($page * $perPage, $total)
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error en endpoint simple: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener clases: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método temporal para diagnosticar
     */
    public function debug()
    {
        $totalClases = Clase::count();
        $totalConRelaciones = Clase::with(['course.magister', 'period', 'room'])->count();

        return response()->json([
            'total_clases' => $totalClases,
            'total_con_relaciones' => $totalConRelaciones,
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true)
        ]);
    }

    /**
     * ===== MÉTODO PÚBLICO (SIN AUTENTICACIÓN) =====
     * Obtener clases públicas para la app móvil
     */
    public function publicIndex(Request $request)
    {
        try {
            $filters = $request->only('magister', 'sala', 'dia', 'period_id');

            $query = Clase::with(['course.magister', 'period', 'room']);

            // Aplicar filtros
            if (!empty($filters['magister'])) {
                $query->whereHas('course.magister', function ($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['magister'] . '%');
                });
            }

            if (!empty($filters['sala'])) {
                $query->whereHas('room', function ($q) use ($filters) {
                    $q->where('name', 'like', '%' . $filters['sala'] . '%');
                });
            }

            if (!empty($filters['dia'])) {
                $query->where('dia', $filters['dia']);
            }

            if (!empty($filters['period_id'])) {
                $query->where('period_id', $filters['period_id']);
            }

            $perPage = $request->get('per_page', 20);
            $clases = $query->orderBy('period_id')
                ->orderByRaw("FIELD(dia, 'Viernes','Sábado')")
                ->orderBy('hora_inicio')
                ->paginate($perPage);

            // Formatear datos para respuesta pública
            $formattedClases = $clases->map(function ($clase) {
                return [
                    'id' => $clase->id,
                    'course_id' => $clase->course_id,
                    'tipo' => $clase->tipo,
                    'period_id' => $clase->period_id,
                    'room_id' => $clase->room_id,
                    'modality' => $clase->modality,
                    'dia' => $clase->dia,
                    'hora_inicio' => $clase->hora_inicio,
                    'hora_fin' => $clase->hora_fin,
                    'url_zoom' => $clase->url_zoom,
                    'encargado' => $clase->encargado,
                    'course' => $clase->course ? [
                        'id' => $clase->course->id,
                        'nombre' => $clase->course->nombre,
                        'magister' => $clase->course->magister ? [
                            'id' => $clase->course->magister->id,
                            'nombre' => $clase->course->magister->nombre,
                            'color' => $clase->course->magister->color,
                        ] : null,
                    ] : null,
                    'period' => $clase->period ? [
                        'id' => $clase->period->id,
                        'numero' => $clase->period->numero,
                        'anio' => $clase->period->anio,
                    ] : null,
                    'room' => $clase->room ? [
                        'id' => $clase->room->id,
                        'name' => $clase->room->name,
                        'location' => $clase->room->location ?? null,
                        'capacity' => $clase->room->capacity ?? null,
                    ] : null,
                    'public_view' => true,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $formattedClases,
                'pagination' => [
                    'current_page' => $clases->currentPage(),
                    'per_page' => $clases->perPage(),
                    'total' => $clases->total(),
                    'last_page' => $clases->lastPage(),
                    'has_more_pages' => $clases->hasMorePages(),
                    'from' => $clases->firstItem(),
                    'to' => $clases->lastItem(),
                ],
                'message' => 'Clases obtenidas exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cargar las clases: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una clase pública específica
     */
    public function publicShow($id)
    {
        try {
            $clase = Clase::with(['course.magister', 'period', 'room'])->find($id);

            if (!$clase) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Clase no encontrada'
                ], 404);
            }

            $formattedClase = [
                'id' => $clase->id,
                'course_id' => $clase->course_id,
                'tipo' => $clase->tipo,
                'period_id' => $clase->period_id,
                'room_id' => $clase->room_id,
                'modality' => $clase->modality,
                'dia' => $clase->dia,
                'hora_inicio' => $clase->hora_inicio,
                'hora_fin' => $clase->hora_fin,
                'url_zoom' => $clase->url_zoom,
                'encargado' => $clase->encargado,
                'course' => $clase->course ? [
                    'id' => $clase->course->id,
                    'nombre' => $clase->course->nombre,
                    'magister' => $clase->course->magister ? [
                        'id' => $clase->course->magister->id,
                        'nombre' => $clase->course->magister->nombre,
                        'color' => $clase->course->magister->color,
                    ] : null,
                ] : null,
                'period' => $clase->period ? [
                    'id' => $clase->period->id,
                    'numero' => $clase->period->numero,
                    'anio' => $clase->period->anio,
                ] : null,
                'room' => $clase->room ? [
                    'id' => $clase->room->id,
                    'name' => $clase->room->name,
                    'location' => $clase->room->location ?? null,
                    'capacity' => $clase->room->capacity ?? null,
                ] : null,
                'public_view' => true,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $formattedClase,
                'message' => 'Clase obtenida exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cargar la clase: ' . $e->getMessage()
            ], 500);
        }
    }
}
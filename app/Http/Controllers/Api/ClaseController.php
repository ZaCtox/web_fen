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
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Listar todos los cursos (con magister y periodo) con paginación.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 30); // Reducir para evitar JSON incompleto

            $courses = Course::with(['magister', 'period'])
                ->orderBy('magister_id')
                ->orderBy('nombre')
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $courses->items(),
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                    'last_page' => $courses->lastPage(),
                    'has_more_pages' => $courses->hasMorePages(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error en courses index: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener cursos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener magísteres con sus cursos agrupados (con paginación)
     */
    public function magistersWithCourses(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10); // Máximo 10 magísteres por página
            $page = $request->get('page', 1);

            // Obtener magísteres con paginación
            $magisters = \App\Models\Magister::with(['courses.period'])
                ->whereHas('courses')
                ->orderBy('nombre')
                ->paginate($perPage, ['*'], 'page', $page);

            // Formatear la respuesta
            $magisterGroups = $magisters->map(function ($magister) {
                return [
                    'magister' => [
                        'id' => $magister->id,
                        'nombre' => $magister->nombre,
                        'encargado' => $magister->encargado,
                        'telefono' => $magister->telefono,
                        'correo' => $magister->correo,
                        'color' => $magister->color,
                        'created_at' => $magister->created_at,
                        'updated_at' => $magister->updated_at
                    ],
                    'courses' => $magister->courses->map(function ($course) {
                        return [
                            'id' => $course->id,
                            'nombre' => $course->nombre,
                            'period' => $course->period ? [
                                'id' => $course->period->id,
                                'numero' => $course->period->numero,
                                'anio' => $course->period->anio,
                                'fecha_inicio' => $course->period->fecha_inicio,
                                'fecha_fin' => $course->period->fecha_fin,
                                'created_at' => $course->period->created_at,
                                'updated_at' => $course->period->updated_at
                            ] : null,
                            'created_at' => $course->created_at,
                            'updated_at' => $course->updated_at
                        ];
                    })
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $magisterGroups,
                'pagination' => [
                    'current_page' => $magisters->currentPage(),
                    'per_page' => $magisters->perPage(),
                    'total' => $magisters->total(),
                    'last_page' => $magisters->lastPage(),
                    'has_more_pages' => $magisters->hasMorePages(),
                    'from' => $magisters->firstItem(),
                    'to' => $magisters->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error en magistersWithCourses: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener magísteres: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo curso.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'magister_id' => 'required|exists:magisters,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        $course = Course::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Curso creado correctamente.',
            'data' => $course->load(['magister', 'period'])
        ], 201);
    }

    /**
     * Mostrar un curso específico.
     */
    public function show(Course $course)
    {
        return response()->json([
            'status' => 'success',
            'data' => $course->load(['magister', 'period'])
        ]);
    }

    /**
     * Actualizar un curso.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'magister_id' => 'required|exists:magisters,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        $course->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Curso actualizado correctamente.',
            'data' => $course->load(['magister', 'period'])
        ]);
    }

    /**
     * Eliminar un curso.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Curso eliminado correctamente.'
        ]);
    }

    /**
     * Obtener solo magísteres (sin cursos) para la lista principal
     */
    public function magistersOnly(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);

            $magisters = \App\Models\Magister::withCount('courses')
                ->whereHas('courses')
                ->orderBy('nombre')
                ->paginate($perPage);

            $magisterData = $magisters->map(function ($magister) {
                return [
                    'id' => $magister->id,
                    'nombre' => $magister->nombre,
                    'encargado' => $magister->encargado,
                    'telefono' => $magister->telefono,
                    'correo' => $magister->correo,
                    'color' => $magister->color,
                    'courses_count' => $magister->courses_count,
                    'created_at' => $magister->created_at,
                    'updated_at' => $magister->updated_at
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $magisterData,
                'pagination' => [
                    'current_page' => $magisters->currentPage(),
                    'per_page' => $magisters->perPage(),
                    'total' => $magisters->total(),
                    'last_page' => $magisters->lastPage(),
                    'has_more_pages' => $magisters->hasMorePages(),
                    'from' => $magisters->firstItem(),
                    'to' => $magisters->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error en magistersOnly: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener magísteres: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener cursos de un magíster específico
     */
    public function magisterCourses(Request $request, $magisterId)
    {
        try {
            $perPage = $request->get('per_page', 20);

            $courses = \App\Models\Course::with('period')
                ->where('magister_id', $magisterId)
                ->orderBy('nombre')
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $courses->items(),
                'pagination' => [
                    'current_page' => $courses->currentPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                    'last_page' => $courses->lastPage(),
                    'has_more_pages' => $courses->hasMorePages(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error en magisterCourses: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener cursos: ' . $e->getMessage()
            ], 500);
        }
    }
}

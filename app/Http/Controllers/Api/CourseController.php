<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Listar todos los cursos (con magister y periodo).
     */
    public function index()
    {
        $courses = Course::with(['magister', 'period'])
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
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
}

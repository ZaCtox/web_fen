<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // GET /api/courses
    public function index()
    {
        $courses = Course::with(['magister','period'])->get();
        return response()->json($courses);
    }

    // GET /api/courses/{id}
    public function show($id)
    {
        $course = Course::with(['magister','period'])->findOrFail($id);
        return response()->json($course);
    }

    // POST /api/courses
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'magister_id' => 'required|exists:magisters,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $course = Course::create($validated);
        return response()->json($course, 201);
    }

    // PUT /api/courses/{id}
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'magister_id' => 'required|exists:magisters,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $course = Course::findOrFail($id);
        $course->update($validated);

        return response()->json($course);
    }

    // DELETE /api/courses/{id}
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Curso eliminado']);
    }
}

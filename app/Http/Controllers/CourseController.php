<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $courses = Course::orderBy('programa')->orderBy('nombre')->get();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('courses.create');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'programa' => 'required|string|max:255'
        ]);

        Course::create($request->only('nombre', 'programa'));

        return redirect()->route('courses.index')->with('success', 'Curso creado correctamente.');
    }

    public function edit(Course $course)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('courses.edit', ['course' => $course]);
    }

    public function update(Request $request, Course $course)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'programa' => 'required|string|max:255'
        ]);

        $course->update($request->only('nombre', 'programa'));

        return redirect()->route('courses.index')->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy(Course $course)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Curso eliminado.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Magister;
use App\Models\Period;
use Illuminate\Http\Request;


class CourseController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $magisters = Magister::with('courses')->orderBy('nombre')->get();

        return view('courses.index', compact('magisters'));
    }

    public function create(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $magisters = Magister::all();
        $selectedMagisterId = $request->magister_id;
        $periods = Period::orderBy('anio')->orderBy('numero')->get();

        return view('courses.create', compact('magisters', 'selectedMagisterId', 'periods'));
    }



    public function store(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'magister_id' => 'required|exists:magisters,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        Course::create($request->only('nombre', 'magister_id', 'period_id'));

        return redirect()->route('courses.index')->with('success', 'Curso creado correctamente.');
    }

    public function edit(Course $course)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $magisters = Magister::all();
        $periods = Period::orderBy('anio')->orderBy('numero')->get();

        return view('courses.edit', compact('course', 'magisters', 'periods'));
    }


    public function update(Request $request, Course $course)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'magister_id' => 'required|exists:magisters,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        $course->update($request->only('nombre', 'magister_id', 'period_id'));

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

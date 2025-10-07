<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Period;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // Evita N+1 al agrupar por año/trimestre en la vista
        $magisters = Magister::with(['courses.period'])
            ->orderBy('nombre')
            ->get();

        return view('courses.index', compact('magisters'));
    }

    public function create(Request $request)
    {
        $magisters = Magister::orderBy('nombre')->get();
        $selectedMagisterId = $request->magister_id;
        $periods = Period::orderBy('anio')->orderBy('numero')->get();

        return view('courses.create', compact('magisters', 'selectedMagisterId', 'periods'));
    }

    public function store(CourseRequest $request)
    {
        Course::create($request->only('nombre', 'magister_id', 'period_id'));

        return redirect()->route('courses.index')->with('success', 'Curso creado correctamente.');
    }

    public function edit(Course $course)
    {
        $magisters = Magister::orderBy('nombre')->get();
        $periods = Period::orderBy('anio')->orderBy('numero')->get();

        return view('courses.edit', compact('course', 'magisters', 'periods'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        $course->update($request->only('nombre', 'magister_id', 'period_id'));

        return redirect()->route('courses.index')->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Curso eliminado.');
    }
}

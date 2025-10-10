<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Magister;
use App\Models\MallaCurricular;
use App\Models\Period;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Obtener cohorte seleccionada (por defecto la más reciente)
        $cohorteSeleccionada = $request->get('cohorte');
        
        // Obtener todas las cohortes disponibles
        $cohortes = Period::select('cohorte')
            ->distinct()
            ->whereNotNull('cohorte')
            ->orderBy('cohorte', 'desc')
            ->pluck('cohorte');

        // Si no hay cohorte seleccionada, usar la más reciente
        if (!$cohorteSeleccionada && $cohortes->isNotEmpty()) {
            $cohorteSeleccionada = $cohortes->first();
        }

        // Cargar magísteres con cursos filtrados por cohorte
        $magisters = Magister::with(['courses' => function($query) use ($cohorteSeleccionada) {
                if ($cohorteSeleccionada) {
                    $query->whereHas('period', function($q) use ($cohorteSeleccionada) {
                        $q->where('cohorte', $cohorteSeleccionada);
                    });
                }
            }, 'courses.period', 'courses.mallaCurricular'])
            ->orderBy('orden')
            ->get();

        return view('courses.index', compact('magisters', 'cohortes', 'cohorteSeleccionada'));
    }

    public function create(Request $request)
    {
        $magisters = Magister::orderBy('orden')->get();
        $selectedMagisterId = $request->magister_id;
        $selectedMallaId = $request->malla_curricular_id;
        $periods = Period::orderBy('cohorte', 'desc')->orderBy('anio')->orderBy('numero')->get();
        
        // Obtener mallas activas para el selector
        $mallas = MallaCurricular::with('magister')
            ->where('activa', true)
            ->orderBy('año_inicio', 'desc')
            ->get();

        return view('courses.create', compact('magisters', 'selectedMagisterId', 'selectedMallaId', 'periods', 'mallas'));
    }

    public function store(CourseRequest $request)
    {
        Course::create($request->only('nombre', 'magister_id', 'malla_curricular_id', 'period_id'));

        return redirect()->route('courses.index')->with('success', 'Curso creado correctamente.');
    }

    public function edit(Course $course)
    {
        $magisters = Magister::orderBy('orden')->get();
        $periods = Period::orderBy('cohorte', 'desc')->orderBy('anio')->orderBy('numero')->get();
        
        // Obtener mallas activas para el selector
        $mallas = MallaCurricular::with('magister')
            ->where('activa', true)
            ->orderBy('año_inicio', 'desc')
            ->get();

        return view('courses.edit', compact('course', 'magisters', 'periods', 'mallas'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        $course->update($request->only('nombre', 'magister_id', 'malla_curricular_id', 'period_id'));

        return redirect()->route('courses.index')->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Curso eliminado.');
    }
}










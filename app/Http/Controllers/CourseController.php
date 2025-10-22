<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Period;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Obtener años de ingreso disponibles
        $aniosIngreso = Period::select('anio_ingreso')
            ->distinct()
            ->whereNotNull('anio_ingreso')
            ->orderBy('anio_ingreso', 'desc')
            ->pluck('anio_ingreso');

        // Año de ingreso seleccionado (por defecto el más reciente)
        $anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());

        // Cargar magísteres con cursos filtrados por año de ingreso
        $magisters = Magister::with(['courses' => function($query) use ($anioIngresoSeleccionado) {
                if ($anioIngresoSeleccionado) {
                    $query->whereHas('period', function($q) use ($anioIngresoSeleccionado) {
                        $q->where('anio_ingreso', $anioIngresoSeleccionado);
                    });
                }
            }, 'courses.period'])
            ->orderBy('orden')
            ->get();

        return view('courses.index', compact('magisters', 'aniosIngreso', 'anioIngresoSeleccionado'));
    }

    public function create(Request $request)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para crear módulos.');
        }
        
        $magisters = Magister::orderBy('orden')->get();
        $selectedMagisterId = $request->magister_id;
        $periods = Period::orderBy('anio_ingreso', 'desc')->orderBy('anio')->orderBy('numero')->get();

        // Obtener todos los cursos para el selector de prerrequisitos
        $allCourses = Course::with('period')->orderBy('nombre')->get();

        return view('courses.create', compact('magisters', 'selectedMagisterId', 'periods', 'allCourses'));
    }

    public function store(CourseRequest $request)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para crear módulos.');
        }
        
        $data = $request->only('nombre', 'sct', 'requisitos', 'magister_id', 'period_id');
        
        // Manejar requisitos: si viene vacío, asignar null
        if (empty($data['requisitos'])) {
            $data['requisitos'] = null;
        }
        
        Course::create($data);

        return redirect()->route('courses.index')->with('success', 'Curso creado correctamente.');
    }

    public function edit(Course $course)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para editar módulos.');
        }
        
        $magisters = Magister::orderBy('orden')->get();
        $periods = Period::orderBy('anio_ingreso', 'desc')->orderBy('anio')->orderBy('numero')->get();

        // Obtener todos los cursos para el selector de prerrequisitos
        $allCourses = Course::with('period')->orderBy('nombre')->get();

        return view('courses.edit', compact('course', 'magisters', 'periods', 'allCourses'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para actualizar módulos.');
        }
        
        $data = $request->only('nombre', 'sct', 'requisitos', 'magister_id', 'period_id');
        
        // Manejar requisitos: si viene vacío, asignar null
        if (empty($data['requisitos'])) {
            $data['requisitos'] = null;
        }
        
        $course->update($data);

        return redirect()->route('courses.index')->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy(Course $course)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para eliminar módulos.');
        }
        
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Curso eliminado.');
    }
}










<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Magister;
use App\Models\Period;
use Illuminate\Http\Request;

class PublicCourseController extends Controller
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

        return view('public.courses', compact('magisters', 'aniosIngreso', 'anioIngresoSeleccionado'));
    }
}

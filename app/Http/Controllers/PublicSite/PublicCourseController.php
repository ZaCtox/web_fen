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
        // Obtener cohortes disponibles
        $cohortes = Period::select('cohorte')
            ->distinct()
            ->whereNotNull('cohorte')
            ->orderBy('cohorte', 'desc')
            ->pluck('cohorte');

        // Cohorte seleccionada (por defecto la más reciente)
        $cohorteSeleccionada = $request->get('cohorte', $cohortes->first());

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

        return view('public.courses', compact('magisters', 'cohortes', 'cohorteSeleccionada'));
    }
}

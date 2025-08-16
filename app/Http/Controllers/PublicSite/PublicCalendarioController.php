<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Models\Period;
use Illuminate\Http\Request;

class PublicCalendarioController extends Controller
{
    public function index(Request $request)
    {
        $periodoActual = Period::orderByDesc('anio')->orderByDesc('numero')->first();
        $fechaInicio = optional($periodoActual)->fecha_inicio?->format('Y-m-d') ?? now()->format('Y-m-d');

        return view('public.calendario', compact('fechaInicio'));
    }
}

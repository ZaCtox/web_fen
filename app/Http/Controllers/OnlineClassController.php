<?php

namespace App\Http\Controllers;

use App\Models\RoomUsage;
use App\Models\Course;
use Illuminate\Http\Request;

class OnlineClassController extends Controller
{
    public function create()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $cursos = Course::with(['magister', 'period'])->orderBy('magister_id')->orderBy('nombre')->get();

        return view('online.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'course_id' => 'required|exists:courses,id',
            'dia' => 'required|in:Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'url_zoom' => 'nullable|url',
        ]);

        RoomUsage::create([
            'room_id' => null,
            'period_id' => $request->period_id,
            'course_id' => $request->course_id,
            'dia' => $request->dia,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'url_zoom' => $request->url_zoom,
        ]);

        return redirect()->route('calendario')->with('success', 'Clase online asignada correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Course;
use App\Models\Period;
use App\Models\Room;
use App\Models\Magister;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ClaseController extends Controller
{
    public function index()
    {
        // Enviamos todos los datos para filtrarlos en el frontend con Alpine.js
        return view('clases.index', [
            'clases' => Clase::with(['course.magister', 'period', 'room'])->get(),
            'rooms' => Room::orderBy('name')->get(),
            'magisters' => Magister::orderBy('nombre')->get(),
        ]);
    }

    public function create()
    {
        $this->authorizeUser();

        $courses = Course::with('magister', 'period')->get();

        $agrupados = $courses->groupBy(
            fn($course) =>
            $course->magister->nombre ?? 'Sin Magíster'
        )->map(function ($group) {
            return $group->map(function ($course) {
                return [
                    'id' => $course->id,
                    'nombre' => $course->nombre,
                    'period_id' => $course->period_id,
                    'periodo' => $course->period?->nombre_completo ?? 'Sin periodo',
                ];
            })->values();
        });

        $rooms = Room::orderBy('name')->get();
        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();

        return view('clases.create', compact('agrupados', 'courses', 'rooms', 'periodos'));
    }

    public function store(Request $request)
    {
        $this->authorizeUser();

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'modality' => 'required|in:presencial,online,hibrida',
            'room_id' => 'nullable|exists:rooms,id',
            'dia' => 'required|in:Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'url_zoom' => 'nullable|url'
        ]);

        Clase::create($validated);

        return redirect()->route('clases.index')->with('success', 'Clase creada correctamente.');
    }

    public function edit(Clase $clase)
    {
        $this->authorizeUser();

        $courses = Course::with('magister')->get();
        $periods = Period::orderByDesc('anio')->orderByDesc('numero')->get();
        $rooms = Room::orderBy('name')->get();

        return view('clases.edit', compact('clase', 'courses', 'periods', 'rooms'));
    }

    public function update(Request $request, Clase $clase)
    {
        $this->authorizeUser();

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'period_id' => 'required|exists:periods,id',
            'modality' => 'required|in:presencial,online,hibrida',
            'room_id' => 'nullable|exists:rooms,id',
            'dia' => 'required|in:Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'url_zoom' => 'nullable|url'
        ]);

        $clase->update($validated);

        return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase)
    {
        $this->authorizeUser();

        $clase->delete();

        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }

    private function authorizeUser()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function exportar(Request $request)
    {

         $this->authorizeUser();

        $query = Clase::with(['course.magister', 'period', 'room'])
            ->when($request->magister, fn($q) =>
                $q->whereHas('course.magister', fn($q2) =>
                    $q2->where('nombre', $request->magister)))
            ->when($request->sala, fn($q) =>
                $q->whereHas('room', fn($q2) =>
                    $q2->where('name', $request->sala)))
            ->when($request->dia, fn($q) =>
                $q->where('dia', $request->dia));

        $clases = $query->orderBy('period_id')->get();

        $pdf = Pdf::loadView('clases.export', compact('clases'))->setPaper('a4', 'landscape');

        return $pdf->download('clases_academicas.pdf');
    }

    public function show(Clase $clase)
    {
        $this->authorizeUser();

        return view('clases.show', compact('clase'));
    }

}

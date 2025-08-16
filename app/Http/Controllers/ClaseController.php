<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Course;
use App\Models\Period;
use App\Models\Room;
use App\Models\Magister;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StoreClaseRequest;
use App\Http\Requests\UpdateClaseRequest;

class ClaseController extends Controller
{
    private function authorizeAccess()
    {
        if (!tieneRol(['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function index()
    {
        $this->authorizeAccess();

        return view('clases.index', [
            'clases' => Clase::with(['course.magister', 'period', 'room'])->get(),
            'rooms' => Room::orderBy('name')->get(),
            'magisters' => Magister::orderBy('nombre')->get(),
        ]);
    }

    public function create()
    {
        $this->authorizeAccess();

        $courses = Course::with('magister', 'period')->get();
        $agrupados = [];

        foreach ($courses as $course) {
            $agrupados[$course->magister->nombre][] = [
                'id' => $course->id,
                'nombre' => $course->nombre,
                'period_id' => $course->period_id,
                'periodo' => $course->period ? $course->period->nombre_completo : 'Sin período',
            ];
        }

        $rooms = Room::orderBy('name')->get();
        $periods = Period::orderBy('anio', 'desc')->orderBy('numero')->get();

        return view('clases.create', [
            'agrupados' => $agrupados,
            'rooms' => $rooms,
            'periods' => $periods,
            'action' => route('clases.store'),
            'method' => 'POST',
            'submitText' => '💾 Crear Clase',
        ]);
    }

    public function store(StoreClaseRequest $request)
    {
        $this->authorizeAccess();

        Clase::create($request->validated());
        return redirect()->route('clases.index')->with('success', 'Clase creada correctamente.');
    }

    public function edit(Clase $clase)
    {
        $this->authorizeAccess();

        [$agrupados, $courses, $rooms, $periods] = $this->referencias();

        return view('clases.edit', compact('clase', 'agrupados', 'courses', 'rooms', 'periods'));
    }

    public function update(UpdateClaseRequest $request, Clase $clase)
    {
        $this->authorizeAccess();

        $clase->update($request->validated());
        return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase)
    {
        $this->authorizeAccess();

        $clase->delete();
        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }

    public function exportar(Request $request)
    {
        $this->authorizeAccess();

        $filters = $request->only('magister', 'sala', 'dia');

        $clases = Clase::with(['course.magister', 'period', 'room'])
            ->filtrar($filters)
            ->orderBy('period_id')
            ->get();

        if ($clases->isEmpty()) {
            return back()->with('warning', 'No se encontraron clases con los filtros aplicados.');
        }

        $nombreArchivo = 'clases_academicas_' . now()->format('Y-m-d_H-i') . '.pdf';

        $pdf = Pdf::loadView('clases.export', compact('clases'))->setPaper('a4', 'landscape');
        return $pdf->download($nombreArchivo);
    }

    public function show(Clase $clase)
    {
        $this->authorizeAccess();

        $clase->load(['course.magister', 'period', 'room']);
        return view('clases.show', compact('clase'));
    }

    private function referencias()
    {
        $courses = Course::with('magister', 'period')->get();
        $agrupados = $courses->groupBy(fn($c) => $c->magister->nombre ?? 'Sin Magíster')
            ->map(fn($group) => $group->map(fn($c) => [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'period_id' => $c->period_id,
                'periodo' => $c->period?->nombre_completo ?? 'Sin periodo',
            ])->values());

        $rooms = Room::orderBy('name')->get();
        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();

        return [$agrupados, $courses, $rooms, $periodos];
    }
}

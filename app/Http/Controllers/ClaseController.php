<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaseRequest;
use App\Http\Requests\UpdateClaseRequest;
use App\Models\Clase;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Period;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClaseController extends Controller
{

    public function index(Request $request)
    {
        $query = Clase::with(['course.magister', 'period', 'room']);

        if ($request->filled('magister')) {
            $query->whereHas('course.magister', fn($q) => $q->where('nombre', $request->magister));
        }

        if ($request->filled('room_id')) {
            $query->whereHas('room', fn($q) => $q->where('name', $request->room_id));
        }

        if ($request->filled('dia')) {
            $query->where('dia', $request->dia);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('anio')) {
            $query->whereHas('period', fn($q) => $q->where('anio', $request->anio));
        }

        if ($request->filled('trimestre')) {
            $query->whereHas('period', fn($q) => $q->where('numero', $request->trimestre));
        }

        $clases = $query
            ->orderBy('period_id')
            ->orderByRaw("FIELD(dia, 'Viernes','Sábado')")
            ->orderBy('hora_inicio')
            ->paginate(12)
            ->appends($request->query());

        $anios = Period::distinct()->orderByDesc('anio')->pluck('anio');
        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();

        return view('clases.index', [
            'clases'    => $clases,
            'rooms'     => Room::orderBy('name')->get(),
            'magisters' => Magister::orderBy('nombre')->get(),
            'anios'     => $anios,
            'periodos'  => $periodos,
        ]);
    }

    public function create()
    {
        $courses = Course::with('magister', 'period')->get();
        $agrupados = [];
        foreach ($courses as $course) {
            $agrupados[$course->magister->nombre][] = [
                'id' => $course->id,
                'nombre' => $course->nombre,
                'period_id' => $course->period_id,
                'periodo' => $course->period?->nombre_completo ?? 'Sin período',
                'anio' => $course->period?->anio ?? null,
                'numero' => $course->period?->numero ?? null,
            ];
        }

        return view('clases.create', [
            'agrupados' => $agrupados,
            'rooms' => Room::orderBy('name')->get(),
            'periods' => Period::orderBy('anio', 'desc')->orderBy('numero')->get(),
            'anios' => Period::distinct()->orderByDesc('anio')->pluck('anio'),
            'trimestres' => Period::distinct()->orderBy('numero')->pluck('numero'),
            'tipos' => ['cátedra', 'taller', 'laboratorio', 'ayudantía'],
            'action' => route('clases.store'),
            'method' => 'POST',
            'submitText' => '💾 Crear Clase',
        ]);
    }

    public function store(StoreClaseRequest $request)
    {
        Clase::create($request->validated());
        return redirect()->route('clases.index')->with('success', 'Clase creada correctamente.');
    }

    public function edit(Clase $clase)
    {
        [$agrupados, $courses, $rooms, $periods] = $this->referencias();

        return view('clases.edit', [
            'clase' => $clase,
            'agrupados' => $agrupados,
            'courses' => $courses,
            'rooms' => $rooms,
            'periods' => $periods,
            'anios' => Period::distinct()->orderByDesc('anio')->pluck('anio'),
            'trimestres' => Period::distinct()->orderBy('numero')->pluck('numero'),
            'tipos' => ['cátedra', 'taller', 'laboratorio', 'ayudantía'],
        ]);
    }

    public function update(UpdateClaseRequest $request, Clase $clase)
    {
        $clase->update($request->validated());
        return redirect()->route('clases.index')->with('success', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase)
    {
        $clase->delete();
        return redirect()->route('clases.index')->with('success', 'Clase eliminada correctamente.');
    }

    public function exportar(Request $request)
    {
        $filters = $request->only('magister', 'sala', 'dia');
        $clases = Clase::with(['course.magister', 'period', 'room'])
            ->filtrar($filters)
            ->orderBy('period_id')
            ->orderByRaw("FIELD(dia, 'Viernes','Sábado')")
            ->orderBy('hora_inicio')
            ->get();

        if ($clases->isEmpty()) {
            return back()->with('warning', 'No se encontraron clases con los filtros aplicados.');
        }

        $nombreArchivo = 'clases_academicas_'.now()->format('Y-m-d_H-i').'.pdf';
        $pdf = Pdf::loadView('clases.export', compact('clases'))->setPaper('a4', 'landscape');

        return $pdf->download($nombreArchivo);
    }

    public function show(Clase $clase)
    {
        $clase->load(['course.magister', 'period', 'room']);
        return view('clases.show', compact('clase'));
    }

    private function referencias()
    {
        $courses = Course::with('magister', 'period')->get();
        $agrupados = $courses->groupBy(fn ($c) => $c->magister->nombre ?? 'Sin Magíster')
            ->map(fn ($group) => $group->map(fn ($c) => [
                'id' => $c->id,
                'nombre' => $c->nombre,
                'period_id' => $c->period_id,
                'periodo' => $c->period?->nombre_completo ?? 'Sin periodo',
                'anio' => $c->period?->anio ?? null,
                'numero' => $c->period?->numero ?? null,
            ])->values());

        return [$agrupados, $courses, Room::orderBy('name')->get(), Period::orderByDesc('anio')->orderBy('numero')->get()];
    }
}

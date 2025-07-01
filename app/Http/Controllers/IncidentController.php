<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403);
        }

        $query = Incident::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('sala')) {
            $query->where('sala', 'like', '%' . $request->sala . '%');
        }

        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        if ($request->filled('semestre')) {
            $query->whereMonth('created_at', $request->semestre == 1 ? '<=' : '>=', 6);
        }

        $incidencias = $query->latest()->paginate(10)->withQueryString();


        return view('incidencias.index', compact('incidencias'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('incidencias.create');
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
            'sala' => 'required|string',
        ]);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('incidencias', $filename, 'public');
            $validated['imagen'] = $filename;
        }

        $validated['user_id'] = Auth::id();
        Incident::create($validated);

        return redirect()->route('incidencias.index')->with('success', 'Incidencia registrada.');
    }

    public function update(Request $request, Incident $incidencia)
    {
        if (!in_array(Auth::user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $incidencia->update([
            'estado' => 'resuelta',
            'resuelta_en' => now(),
        ]);

        return redirect()->back()->with('success', 'Incidencia marcada como resuelta.');
    }
    public function show(Incident $incidencia)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        return view('incidencias.show', compact('incidencia'));
    }

    public function estadisticas(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403);
        }

        $query = Incident::query();

        // Filtros
        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        if ($request->filled('semestre')) {
            if ($request->semestre == 1) {
                $query->whereMonth('created_at', '<=', 6);
            } else {
                $query->whereMonth('created_at', '>=', 7);
            }
        }

        if ($request->filled('sala')) {
            $query->where('sala', 'like', '%' . $request->sala . '%');
        }

        $incidenciasFiltradas = $query->get();

        $porSala = $incidenciasFiltradas->groupBy('sala')->map->count();

        $porEstado = $incidenciasFiltradas->groupBy('estado')->map->count();

        $porSemestre = $incidenciasFiltradas->groupBy(function ($item) {
            $semestre = ($item->created_at->month <= 6) ? '1' : '2';
            $anio = $item->created_at->year;
            return "$anio - S$semestre";
        })->map->count();

        return view('incidencias.estadisticas', [
            'porSala' => $porSala,
            'porEstado' => $porEstado,
            'porSemestre' => $porSemestre,
        ]);
    }

    public function exportarPDF(Request $request)
    {
        $query = Incident::query();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('sala')) {
            $query->where('sala', 'like', '%' . $request->sala . '%');
        }

        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        if ($request->filled('semestre')) {
            $request->semestre == 1
                ? $query->whereMonth('created_at', '<=', 6)
                : $query->whereMonth('created_at', '>=', 7);
        }

        $incidencias = $query->latest()->get();

        $pdf = Pdf::loadView('incidencias.pdf', compact('incidencias'));
        return $pdf->download('bitacora_incidencias.pdf');
    }
}

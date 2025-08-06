<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Room;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Cloudinary\Api\Upload\UploadApi;
use Carbon\Carbon;


class IncidentController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403);
        }

        $query = Incident::with('room');
        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        if ($request->filled('period_id')) {
            $periodo = Period::find($request->period_id);
            if ($periodo) {
                $query->whereBetween('created_at', [$periodo->fecha_inicio, $periodo->fecha_fin]);
            }
        }

        // 🟡 Filtro "ver solo históricos"
        if ($request->filled('historico')) {
            $rangos = $periodos->map(fn($p) => [$p->fecha_inicio, $p->fecha_fin]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->whereNotBetween('created_at', [$inicio, $fin]);
                }
            });

            // Años solo de incidencias fuera de los periodos
            $anios = Incident::all()
                ->filter(function ($inc) use ($periodos) {
                    return !$periodos->contains(function ($p) use ($inc) {
                        return $inc->created_at->between($p->fecha_inicio, $p->fecha_fin);
                    });
                })
                ->pluck('created_at')
                ->map(fn($dt) => $dt->format('Y'))
                ->unique()
                ->sortDesc()
                ->values();
        } else {
            // Años SOLO dentro de periodos
            $anios = Incident::all()
                ->filter(function ($inc) use ($periodos) {
                    return $periodos->contains(function ($p) use ($inc) {
                        return $inc->created_at->between($p->fecha_inicio, $p->fecha_fin);
                    });
                })
                ->pluck('created_at')
                ->map(fn($dt) => $dt->format('Y'))
                ->unique()
                ->sortDesc()
                ->values();
        }

        $incidencias = $query->latest()->paginate(10)->withQueryString();
        $salas = Room::orderBy('name')->get();


        return view('incidencias.index', compact('incidencias', 'salas', 'anios', 'periodos'));
    }

    public function create()
    {
        $this->authorizeAccess();

        $salas = Room::orderBy('name')->get();
        return view('incidencias.create', compact('salas'));
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $uploadedFile = $request->file('imagen')->getRealPath();

            try {
                $cloudinaryUpload = (new UploadApi())->upload($uploadedFile, [
                    'folder' => 'incidencias'
                ]);

                $validated['imagen'] = $cloudinaryUpload['secure_url'];
                $validated['public_id'] = $cloudinaryUpload['public_id'];
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['imagen' => 'Error al subir a Cloudinary: ' . $e->getMessage()]);
            }
        }

        $validated['user_id'] = Auth::id();
        Incident::create($validated);

        return redirect()->route('incidencias.index')->with('success', 'Incidencia registrada.');
    }

    public function update(Request $request, Incident $incidencia)
    {
        $this->authorizeAccess();

        $incidencia->update([
            'estado' => 'resuelta',
            'resuelta_en' => now(),
        ]);

        return redirect()->back()->with('success', 'Incidencia marcada como resuelta.');
    }

    public function show(Incident $incidencia)
    {
        $this->authorizeAccess();

        $incidencia->load('room', 'user');
        return view('incidencias.show', compact('incidencia'));
    }

    public function estadisticas(Request $request)
    {
        $this->authorizeAccess();

        // Obtener todos los períodos ordenados
        $periodos = Period::orderBy('fecha_inicio')->orderBy('numero')->get();

        // Extraer años reales desde fecha_inicio (no desde campo 'anio')
        $aniosUnicos = $periodos
            ->map(fn($p) => Carbon::parse($p->fecha_inicio)->year)
            ->unique()
            ->sort()
            ->values();

        // Filtro y query base
        $query = Incident::query();

        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $incidenciasFiltradas = $query->with('room')->get();

        // Agrupar por sala
        $porSala = $incidenciasFiltradas->groupBy(fn($i) => $i->room->name ?? 'Sin Sala')->map->count();
        // Agrupar por estado
        $porEstado = $incidenciasFiltradas->groupBy('estado')->map->count();

        // Filtrar períodos
        $periodosFiltrados = $periodos->filter(function ($p) use ($request) {
            $anioPeriodo = Carbon::parse($p->fecha_inicio)->year;
            if ($request->filled('anio') && $anioPeriodo != $request->anio)
                return false;
            if ($request->filled('trimestre') && $p->numero != $request->trimestre)
                return false;
            return true;
        });

        // Agrupar por trimestre
        $porTrimestre = collect();

        // Obtener todas las incidencias con sala y fecha de creación
        foreach ($incidenciasFiltradas as $incidencia) {
            // Buscar a qué periodo pertenece según la fecha de la incidencia
            $periodo = $periodos->first(function ($p) use ($incidencia) {
                return $incidencia->created_at->between($p->fecha_inicio, $p->fecha_fin);
            });

            if ($periodo) {
                $clave = $incidencia->created_at->year . ' - T' . $periodo->numero;
                $porTrimestre[$clave] = ($porTrimestre[$clave] ?? 0) + 1;
            }
        }

        $porTrimestre = $porTrimestre->sortKeys();

        // Opciones para el select año
        $periodos = Period::orderBy('fecha_inicio')->orderBy('numero')->get();
        $rangos = $periodos->map(fn($p) => [$p->fecha_inicio, $p->fecha_fin]);

        $anios = Incident::selectRaw('YEAR(created_at) as anio')
            ->distinct()
            ->get()
            ->pluck('anio')
            ->filter(function ($anio) use ($request, $rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $anioInicio = Carbon::parse($inicio)->year;
                    $anioFin = Carbon::parse($fin)->year;
                    if ($anio >= $anioInicio && $anio <= $anioFin) {
                        return !$request->filled('historico'); // si no está marcado, solo muestra los dentro
                    }
                }
                return $request->filled('historico'); // si está marcado, muestra los fuera
            })
            ->sortDesc()
            ->values();


        // Datos a la vista
        $salas = Room::orderBy('name')->get();

        return view('incidencias.estadisticas', compact(
            'porSala',
            'porEstado',
            'porTrimestre',
            'salas',
            'periodos',
            'anios',
        ));
    }





    public function exportarPDF(Request $request)
    {
        $this->authorizeAccess();

        $query = Incident::with('room', 'user');

        // Año
        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        // Estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Sala
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // Trimestre usando períodos
        if ($request->filled('trimestre')) {
            $periodos = Period::where('numero', $request->trimestre);

            if ($request->filled('anio')) {
                $periodos = $periodos->whereYear('fecha_inicio', $request->anio);
            }

            $rangos = $periodos->get()->map(fn($p) => [$p->fecha_inicio, $p->fecha_fin]);

            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->orWhereBetween('created_at', [$inicio, $fin]);
                }
            });
        }

        // Filtrar solo históricos
        if ($request->filled('historico')) {
            $periodos = Period::all();
            $rangos = $periodos->map(fn($p) => [$p->fecha_inicio, $p->fecha_fin]);

            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->whereNotBetween('created_at', [$inicio, $fin]);
                }
            });
        }
        $nombre = 'bitacora_incidencias_' . now()->format('Y-m-d_H-i') . '.pdf';
           $usuario = Auth::user(); 

        $incidencias = $query->latest()->get();
        $fechaActual = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('incidencias.pdf', compact('incidencias', 'usuario', 'fechaActual'));
        return $pdf->download($nombre);
    }



    public function destroy(Incident $incidencia)
    {
        $this->authorizeAccess();

        if ($incidencia->public_id) {
            try {
                (new UploadApi())->destroy($incidencia->public_id);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['imagen' => 'Error al eliminar en Cloudinary: ' . $e->getMessage()]);
            }
        }

        $incidencia->delete();

        return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada correctamente.');
    }

    private function authorizeAccess()
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }
}

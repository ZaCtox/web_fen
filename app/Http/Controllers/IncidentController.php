<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentLog;
use App\Models\Period;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    private function authorizeAccess()
    {
        if (!tieneRol(['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAccess();

        $query = Incident::with('room');
        $periodos = Period::all()->map(fn($p) => ['anio' => Carbon::parse($p->fecha_inicio)->year] + $p->toArray());

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titulo', 'like', '%'.$request->search.'%')
                  ->orWhere('descripcion', 'like', '%'.$request->search.'%');
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

        if ($request->filled('trimestre')) {
            $periodosFiltrados = Period::where('numero', $request->trimestre);
            if ($request->filled('anio')) {
                $periodosFiltrados = $periodosFiltrados->whereYear('fecha_inicio', $request->anio);
            }
            $rangos = $periodosFiltrados->get()->map(fn($p) => [$p->fecha_inicio, $p->fecha_fin]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->orWhereBetween('created_at', [$inicio, $fin]);
                }
            });
        }

        // Histórico
        if ($request->filled('historico')) {
            $rangos = $periodos->map(fn($p) => [$p['fecha_inicio'], $p['fecha_fin']]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->whereNotBetween('created_at', [$inicio, $fin]);
                }
            });
        }

        $incidencias = $query->latest()->paginate(10)->withQueryString();
        $salas = Room::orderBy('name')->get();

        $anios = Incident::all()
            ->pluck('created_at')
            ->map(fn($dt) => Carbon::parse($dt)->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

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
            'nro_ticket' => 'nullable|string|max:255|unique:incidents,nro_ticket',
        ]);

        if ($request->hasFile('imagen')) {
            $uploadedFile = $request->file('imagen')->getRealPath();
            try {
                $cloudinaryUpload = (new UploadApi)->upload($uploadedFile, ['folder' => 'incidencias']);
                $validated['imagen'] = $cloudinaryUpload['secure_url'];
                $validated['public_id'] = $cloudinaryUpload['public_id'];
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['imagen' => 'Error al subir a Cloudinary: '.$e->getMessage()]);
            }
        }

        $validated['user_id'] = Auth::id();
        Incident::create($validated);

        return redirect()->route('incidencias.index')->with('success', 'Incidencia registrada.');
    }

    public function update(Request $request, Incident $incidencia)
    {
        $this->authorizeAccess();

        if (in_array($incidencia->estado, ['resuelta', 'no_resuelta'])) {
            return redirect()->back()->with('error', 'No se puede modificar una incidencia ya cerrada.');
        }

        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_revision,resuelta,no_resuelta',
            'nro_ticket' => 'nullable|string|max:255',
            'comentario' => 'nullable|string|max:1000',
        ]);

        if ($validated['estado'] === 'resuelta' && $incidencia->estado !== 'resuelta') {
            $validated['resuelta_en'] = now();
            $validated['resolved_by'] = Auth::id();
        }

        $incidencia->update($validated);

        IncidentLog::create([
            'incident_id' => $incidencia->id,
            'user_id' => Auth::id(),
            'estado' => $validated['estado'],
            'comentario' => $validated['comentario'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Incidencia actualizada.');
    }

    public function show(Incident $incidencia)
    {
        $this->authorizeAccess();
        $incidencia->load('room', 'user', 'logs.user');

        $dentroDePeriodo = Period::where('fecha_inicio', '<=', $incidencia->created_at)
            ->where('fecha_fin', '>=', $incidencia->created_at)
            ->exists();

        return view('incidencias.show', compact('incidencia', 'dentroDePeriodo'));
    }

    public function estadisticas(Request $request)
    {
        $this->authorizeAccess();

        $periodos = Period::orderBy('fecha_inicio')->orderBy('numero')->get();
        $aniosUnicos = $periodos->map(fn($p) => Carbon::parse($p->fecha_inicio)->year)->unique()->sort()->values();

        $query = Incident::query();
        if ($request->filled('anio')) $query->whereYear('created_at', $request->anio);
        if ($request->filled('room_id')) $query->where('room_id', $request->room_id);

        $incidenciasFiltradas = $query->with('room')->get();

        $porSala = $incidenciasFiltradas->groupBy(fn ($i) => $i->room->name ?? 'Sin Sala')->map->count();
        $porEstado = $incidenciasFiltradas->groupBy('estado')->map->count();

        $porTrimestre = collect();
        foreach ($incidenciasFiltradas as $incidencia) {
            $periodo = $periodos->first(fn($p) => $incidencia->created_at->between($p->fecha_inicio, $p->fecha_fin));
            if ($periodo) {
                $clave = $incidencia->created_at->year.' - T'.$periodo->numero;
                $porTrimestre[$clave] = ($porTrimestre[$clave] ?? 0) + 1;
            }
        }

        $porTrimestre = $porTrimestre->sortKeys();
        $rangos = $periodos->map(fn($p) => [$p->fecha_inicio, $p->fecha_fin]);
        $anios = Incident::selectRaw('YEAR(created_at) as anio')->distinct()->pluck('anio')->sortDesc();

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

        if ($request->filled('anio')) $query->whereYear('created_at', $request->anio);
        if ($request->filled('estado')) $query->where('estado', $request->estado);
        if ($request->filled('room_id')) $query->where('room_id', $request->room_id);

        if ($request->filled('trimestre')) {
            $periodos = Period::where('numero', $request->trimestre);
            if ($request->filled('anio')) $periodos = $periodos->whereYear('fecha_inicio', $request->anio);
            $rangos = $periodos->get()->map(fn($p) => [$p->fecha_inicio, $p->fecha_fin]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) $q->orWhereBetween('created_at', [$inicio, $fin]);
            });
        }

        if ($request->filled('historico')) {
            $rangos = Period::all()->map(fn($p) => [$p->fecha_inicio, $p->fecha_fin]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) $q->whereNotBetween('created_at', [$inicio, $fin]);
            });
        }

        $nombre = 'bitacora_incidencias_'.now()->format('Y-m-d_H-i').'.pdf';
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
                (new UploadApi)->destroy($incidencia->public_id);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['imagen' => 'Error al eliminar en Cloudinary: '.$e->getMessage()]);
            }
        }

        $incidencia->delete();
        return redirect()->route('incidencias.index')->with('success', 'Incidencia eliminada correctamente.');
    }
}

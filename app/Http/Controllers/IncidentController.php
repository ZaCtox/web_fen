<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Room;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Cloudinary\Api\Upload\UploadApi;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403);
        }

        $query = Incident::with('room');

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

        $incidencias = $query->latest()->paginate(10)->withQueryString();

        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get();
        $salas = Room::orderBy('name')->get();
        $anios = Incident::selectRaw('YEAR(created_at) as anio')->distinct()->pluck('anio')->sortDesc();

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

        $query = Incident::query();

        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        if ($request->filled('semestre')) {
            $request->semestre == 1
                ? $query->whereMonth('created_at', '<=', 6)
                : $query->whereMonth('created_at', '>=', 7);
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $incidenciasFiltradas = $query->with('room')->get();

        $porSala = $incidenciasFiltradas->groupBy(fn($i) => $i->room->name ?? 'Sin Sala')->map->count();
        $porEstado = $incidenciasFiltradas->groupBy('estado')->map->count();
        $porSemestre = $incidenciasFiltradas->groupBy(function ($item) {
            $semestre = ($item->created_at->month <= 6) ? '1' : '2';
            $anio = $item->created_at->year;
            return "$anio - S$semestre";
        })->map->count();

        $salas = Room::orderBy('name')->get(); // ✅ Incluido para el filtro
        $anios = Incident::selectRaw('YEAR(created_at) as anio')->distinct()->pluck('anio')->sortDesc(); // opcional
        $periodos = Period::orderByDesc('anio')->orderBy('numero')->get(); // si quieres agregarlo después

        return view('incidencias.estadisticas', [
            'porSala' => $porSala,
            'porEstado' => $porEstado,
            'porSemestre' => $porSemestre,
            'salas' => $salas,
            'anios' => $anios,
        ]);
    }


    public function exportarPDF(Request $request)
    {
        $query = Incident::with('room', 'user'); // 👈 importante incluir relaciones

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
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

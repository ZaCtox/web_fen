<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Cloudinary\Api\Upload\UploadApi;
use App\Models\Trimestre;

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

        if ($request->filled('trimestre_id')) {
            $trimestre = Trimestre::find($request->trimestre_id);
            if ($trimestre) {
                $query->whereBetween('created_at', [$trimestre->fecha_inicio, $trimestre->fecha_fin]);
            }
        }


        $incidencias = $query->latest()->paginate(10)->withQueryString();

        $trimestres = Trimestre::orderBy('año')->orderBy('numero')->get();
        $salas = Room::orderBy('name')->get();
        $anios = Incident::selectRaw('YEAR(created_at) as anio')->distinct()->pluck('anio')->sortDesc();

        return view('incidencias.index', compact('incidencias', 'salas', 'anios', 'trimestres'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $salas = Room::orderBy('name')->get();
        return view('incidencias.create', compact('salas'));
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string',
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

        $incidencia->load('room', 'user');
        return view('incidencias.show', compact('incidencia'));
    }

    public function estadisticas(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403);
        }

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

        return view('incidencias.estadisticas', [
            'porSala' => $porSala,
            'porEstado' => $porEstado,
            'porSemestre' => $porSemestre,
        ]);
    }

    public function exportarPDF(Request $request)
    {
        $query = Incident::with('room');

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
        if (!in_array(auth()->user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

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
}

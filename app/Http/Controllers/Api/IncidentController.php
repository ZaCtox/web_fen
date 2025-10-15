<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\IncidentLog;
use App\Models\Period;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Api\Upload\UploadApi;
use Carbon\Carbon;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Incident::with(['room', 'user']);

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

        if ($request->filled('trimestre')) {
            $periodos = Period::where('numero', $request->trimestre);
            if ($request->filled('anio')) {
                $periodos->whereYear('fecha_inicio', $request->anio);
            }
            $rangos = $periodos->get()->map(fn($p) => [$p->fecha_inicio, $p->fecha_fin]);

            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->orWhereBetween('created_at', [$inicio, $fin]);
                }
            });
        }

        $incidencias = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $incidencias,
            'message' => 'Incidencias obtenidas exitosamente'
        ], 200);
    }

    public function show(Incident $incident)
    {
        $incident->load(['room', 'user', 'logs.user']);
        return response()->json($incident, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'required|string',
            'room_id'      => 'required|exists:rooms,id',
            'imagen'       => 'nullable|image|max:2048',
            'nro_ticket'   => 'nullable|string|max:255|unique:incidents,nro_ticket',
        ]);

        if ($request->hasFile('imagen')) {
            try {
                $cloudinaryUpload = (new UploadApi())->upload(
                    $request->file('imagen')->getRealPath(),
                    ['folder' => 'incidencias']
                );

                $validated['imagen'] = $cloudinaryUpload['secure_url'];
                $validated['public_id'] = $cloudinaryUpload['public_id'];
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error al subir a Cloudinary: ' . $e->getMessage()], 500);
            }
        }

        $validated['user_id'] = Auth::id();
        $incident = Incident::create($validated);

        return response()->json([
            'message' => 'Incidencia registrada.',
            'incident' => $incident
        ], 201);
    }

    public function update(Request $request, Incident $incident)
    {
        if (in_array($incident->estado, ['resuelta', 'no_resuelta'])) {
            return response()->json(['error' => 'No se puede modificar una incidencia ya cerrada.'], 400);
        }

        $validated = $request->validate([
            'estado'     => 'required|in:pendiente,en_revision,resuelta,no_resuelta',
            'nro_ticket' => 'nullable|string|max:255',
            'comentario' => 'nullable|string|max:1000',
        ]);

        if ($validated['estado'] === 'resuelta' && $incident->estado !== 'resuelta') {
            $validated['resuelta_en'] = now();
            $validated['resolved_by'] = Auth::id();
        }

        $incident->update($validated);

        IncidentLog::create([
            'incident_id' => $incident->id,
            'user_id'     => Auth::id(),
            'estado'      => $validated['estado'],
            'comentario'  => $validated['comentario'] ?? null,
        ]);

        return response()->json([
            'message' => 'Incidencia actualizada.',
            'incident' => $incident
        ], 200);
    }

    public function destroy(Incident $incident)
    {
        if ($incident->public_id) {
            try {
                (new UploadApi())->destroy($incident->public_id);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error al eliminar en Cloudinary: ' . $e->getMessage()], 500);
            }
        }

        $incident->delete();

        return response()->json(['message' => 'Incidencia eliminada correctamente.'], 200);
    }

    public function estadisticas(Request $request)
    {
        $query = Incident::with('room');

        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $incidencias = $query->get();

        $porSala = $incidencias->groupBy(fn($i) => $i->room->name ?? 'Sin Sala')->map->count();
        $porEstado = $incidencias->groupBy('estado')->map->count();

        $periodos = Period::orderBy('fecha_inicio')->orderBy('numero')->get();
        $porTrimestre = collect();

        foreach ($incidencias as $incidencia) {
            $periodo = $periodos->first(fn($p) => $incidencia->created_at->between($p->fecha_inicio, $p->fecha_fin));
            if ($periodo) {
                $clave = $incidencia->created_at->year . ' - T' . $periodo->numero;
                $porTrimestre[$clave] = ($porTrimestre[$clave] ?? 0) + 1;
            }
        }

        return response()->json([
            'porSala'      => $porSala,
            'porEstado'    => $porEstado,
            'porTrimestre' => $porTrimestre->sortKeys(),
        ], 200);
    }
}

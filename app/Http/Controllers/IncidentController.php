<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentLog;
use App\Models\Magister;
use App\Models\Period;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{

    public function index(Request $request)
    {
        $query = Incident::with('room');
        
        // Obtener años de ingreso disponibles
        $aniosIngreso = Period::select('anio_ingreso')
            ->distinct()
            ->whereNotNull('anio_ingreso')
            ->orderBy('anio_ingreso', 'desc')
            ->pluck('anio_ingreso');

        // Año de ingreso seleccionado (por defecto el más reciente)
        $anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());
        
        // Obtener períodos del año de ingreso seleccionado
        $periodos = Period::where('anio_ingreso', $anioIngresoSeleccionado)->get();
        
        // Filtrar por rol del usuario
        $user = Auth::user();
        $rolesQueVenTodas = ['administrador', 'director_administrativo', 'técnico', 'auxiliar', 'asistente_postgrado'];
        
        if (!in_array($user->rol, $rolesQueVenTodas)) {
            // Los usuarios normales solo ven las incidencias que ellos crearon
            $query->where('user_id', $user->id);
        }
        // Los roles autorizados ven todas las incidencias

        if ($request->filled('busqueda')) {
            $query->where(fn ($q) => $q->where('titulo', 'like', '%'.$request->busqueda.'%')
                ->orWhere('descripcion', 'like', '%'.$request->busqueda.'%'));
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
            $periodosFiltrados = Period::where('numero', $request->trimestre)
                ->where('anio_ingreso', $anioIngresoSeleccionado);
            if ($request->filled('anio')) {
                $periodosFiltrados = $periodosFiltrados->whereYear('fecha_inicio', $request->anio);
            }
            $rangos = $periodosFiltrados->get()->map(fn ($p) => [$p->fecha_inicio, $p->fecha_fin]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->orWhereBetween('created_at', [$inicio, $fin]);
                }
            });
        }

        if ($request->filled('historico')) {
            $rangos = Period::all()->map(fn ($p) => [$p->fecha_inicio, $p->fecha_fin]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->whereNotBetween('created_at', [$inicio, $fin]);
                }
            });
        }

        if ($request->filled('magister_id')) {
            $query->where('magister_id', $request->magister_id);
        }

        $incidencias = $query->latest()->paginate(10)->withQueryString();
        $salas = Room::orderBy('name')->get();
        $magisters = Magister::orderBy('orden')->get();

        // Calcular estadísticas sobre TODAS las incidencias del usuario (sin filtros)
        $queryEstadisticas = Incident::query();
        if (!in_array($user->rol, $rolesQueVenTodas)) {
            $queryEstadisticas->where('user_id', $user->id);
        }
        
        $estadisticas = [
            'total' => $queryEstadisticas->count(),
            'pendientes' => $queryEstadisticas->where('estado', 'pendiente')->count(),
            'en_revision' => $queryEstadisticas->where('estado', 'en_revision')->count(),
            'resueltas' => $queryEstadisticas->where('estado', 'resuelta')->count(),
            'no_resueltas' => $queryEstadisticas->where('estado', 'no_resuelta')->count(),
        ];

        // Obtener años SOLO del año de ingreso seleccionado (para filtro normal)
        $anios = Period::where('anio_ingreso', $anioIngresoSeleccionado)
            ->get()
            ->map(fn($p) => $p->fecha_inicio->year)
            ->unique()
            ->sort()
            ->values();
            
        // Obtener años históricos (años de incidencias que NO están en el año de ingreso seleccionado)
        $aniosPeriodos = Period::where('anio_ingreso', $anioIngresoSeleccionado)
            ->get()
            ->map(fn($p) => $p->fecha_inicio->year)
            ->unique()
            ->toArray();
            
        $aniosHistoricos = Incident::selectRaw('YEAR(created_at) as anio')
            ->distinct()
            ->whereNotIn(DB::raw('YEAR(created_at)'), $aniosPeriodos)
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        return view('incidencias.index', compact('incidencias', 'salas', 'magisters', 'anios', 'aniosHistoricos', 'periodos', 'estadisticas', 'aniosIngreso', 'anioIngresoSeleccionado'));
    }

    public function create()
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para crear incidencias.');
        }
        
        $salas = Room::orderBy('name')->get();
        $magisters = Magister::orderBy('orden')->get();

        return view('incidencias.create', compact('salas', 'magisters'));
    }

    public function store(Request $request)
    {
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para crear incidencias.');
        }
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'magister_id' => 'nullable|exists:magisters,id',
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
        // Debug: Log the request data
        \Log::info('Incident Update Request:', [
            'incident_id' => $incidencia->id,
            'current_estado' => $incidencia->estado,
            'user_role' => Auth::user()->rol,
            'request_data' => $request->all()
        ]);

        if (in_array($incidencia->estado, ['resuelta', 'no_resuelta'])) {
            return redirect()->back()->with('error', 'No se puede modificar una incidencia ya cerrada.');
        }

        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_revision,resuelta,no_resuelta',
            'nro_ticket' => 'nullable|string|max:255',
            'comentario' => 'nullable|string|max:1000',
        ]);

        \Log::info('Validated data:', $validated);

        // Guardar el estado anterior para crear notificación
        $estadoAnterior = $incidencia->estado;

        if ($validated['estado'] === 'resuelta' && $incidencia->estado !== 'resuelta') {
            $validated['resuelta_en'] = now();
            $validated['resolved_by'] = Auth::id();
        }

        $incidencia->update($validated);

        // Crear notificación si el estado cambió
        if ($estadoAnterior !== $validated['estado']) {
            crearNotificacionCambioEstado($incidencia->id, $estadoAnterior, $validated['estado'], Auth::user());
        }

        IncidentLog::create([
            'incident_id' => $incidencia->id,
            'user_id' => Auth::id(),
            'estado' => $validated['estado'],
            'comentario' => $validated['comentario'] ?? null,
        ]);

        \Log::info('Incident updated successfully');

        return redirect()->back()->with('success', 'Incidencia actualizada.');
    }

    public function show(Incident $incidencia)
    {
        // Verificar permisos de acceso
        $user = Auth::user();
        $rolesQueVenTodas = ['administrador', 'director_administrativo', 'técnico', 'auxiliar', 'asistente_postgrado'];
        
        if (!in_array($user->rol, $rolesQueVenTodas) && $incidencia->user_id !== $user->id) {
            abort(403, 'No tienes permisos para ver esta incidencia.');
        }

        $incidencia->load('room', 'user', 'logs.user');

        $dentroDePeriodo = Period::where('fecha_inicio', '<=', $incidencia->created_at)
            ->where('fecha_fin', '>=', $incidencia->created_at)
            ->exists();

        return view('incidencias.show', compact('incidencia', 'dentroDePeriodo'));
    }

    public function estadisticas(Request $request)
    {
        // Obtener años de ingreso disponibles
        $aniosIngreso = Period::select('anio_ingreso')
            ->distinct()
            ->whereNotNull('anio_ingreso')
            ->orderBy('anio_ingreso', 'desc')
            ->pluck('anio_ingreso');

        // Año de ingreso seleccionado (por defecto el más reciente)
        $anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());
        
        $periodos = Period::where('anio_ingreso', $anioIngresoSeleccionado)
            ->orderBy('fecha_inicio')
            ->orderBy('numero')
            ->get();
        $aniosUnicos = $periodos->map(fn ($p) => Carbon::parse($p->fecha_inicio)->year)->unique()->sort()->values();

        $query = Incident::query();
        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('trimestre')) {
            $periodosFiltrados = Period::where('numero', $request->trimestre)
                ->where('anio_ingreso', $anioIngresoSeleccionado);
            if ($request->filled('anio')) {
                $periodosFiltrados = $periodosFiltrados->whereYear('fecha_inicio', $request->anio);
            }
            $rangos = $periodosFiltrados->get()->map(fn ($p) => [$p->fecha_inicio, $p->fecha_fin]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->orWhereBetween('created_at', [$inicio, $fin]);
                }
            });
        }
        if ($request->filled('historico')) {
            // Para modo histórico, excluir incidencias dentro de períodos definidos
            $query->whereNotExists(function ($subquery) {
                $subquery->select(\DB::raw(1))
                    ->from('periods')
                    ->whereRaw('incidents.created_at BETWEEN periods.fecha_inicio AND periods.fecha_fin');
            });
        }

        if ($request->filled('magister_id')) {
            $query->where('magister_id', $request->magister_id);
        }

        $incidenciasFiltradas = $query->with('room')->get();

        $porSala = $incidenciasFiltradas->groupBy(fn ($i) => $i->room->name ?? 'Sin Sala')->map->count();
        $porEstado = $incidenciasFiltradas->groupBy('estado')->map->count();

        $porTrimestre = collect();
        foreach ($incidenciasFiltradas as $incidencia) {
            $periodo = $periodos->first(fn ($p) => $incidencia->created_at->between($p->fecha_inicio, $p->fecha_fin));
            if ($periodo) {
                $clave = $incidencia->created_at->year.' - T'.$periodo->numero;
                $porTrimestre[$clave] = ($porTrimestre[$clave] ?? 0) + 1;
            }
        }

        $porTrimestre = $porTrimestre->sortKeys();
        $rangos = $periodos->map(fn ($p) => [$p->fecha_inicio, $p->fecha_fin]);
        
        // Obtener años SOLO del año de ingreso seleccionado (para filtro normal)
        $anios = Period::where('anio_ingreso', $anioIngresoSeleccionado)
            ->get()
            ->map(fn($p) => $p->fecha_inicio->year)
            ->unique()
            ->sort()
            ->values();
            
        // Obtener años históricos (años de incidencias que NO están en el año de ingreso seleccionado)
        $aniosPeriodos = Period::where('anio_ingreso', $anioIngresoSeleccionado)
            ->get()
            ->map(fn($p) => $p->fecha_inicio->year)
            ->unique()
            ->toArray();
            
        $aniosHistoricos = Incident::selectRaw('YEAR(created_at) as anio')
            ->distinct()
            ->whereNotIn(DB::raw('YEAR(created_at)'), $aniosPeriodos)
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        $salas = Room::orderBy('name')->get();
        $magisters = Magister::orderBy('orden')->get();

        return view('incidencias.estadisticas', compact(
            'porSala',
            'porEstado',
            'porTrimestre',
            'salas',
            'magisters',
            'periodos',
            'anios',
            'aniosHistoricos',
            'incidenciasFiltradas',
            'aniosIngreso',
            'anioIngresoSeleccionado'
        ));
    }

    public function exportarPDF(Request $request)
    {
        // Obtener año de ingreso seleccionado (por defecto el más reciente)
        $aniosIngreso = Period::select('anio_ingreso')
            ->distinct()
            ->whereNotNull('anio_ingreso')
            ->orderBy('anio_ingreso', 'desc')
            ->pluck('anio_ingreso');
        
        $anioIngresoSeleccionado = $request->get('anio_ingreso', $aniosIngreso->first());

        $query = Incident::with('room', 'user');

        if ($request->filled('busqueda')) {
            $query->where(fn ($q) => $q->where('titulo', 'like', '%'.$request->busqueda.'%')
                ->orWhere('descripcion', 'like', '%'.$request->busqueda.'%'));
        }
        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->filled('trimestre')) {
            $periodos = Period::where('numero', $request->trimestre)
                ->where('anio_ingreso', $anioIngresoSeleccionado);
            if ($request->filled('anio')) {
                $periodos = $periodos->whereYear('fecha_inicio', $request->anio);
            }
            $rangos = $periodos->get()->map(fn ($p) => [$p->fecha_inicio, $p->fecha_fin]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->orWhereBetween('created_at', [$inicio, $fin]);
                }
            });
        }

        if ($request->filled('historico')) {
            $rangos = Period::all()->map(fn ($p) => [$p->fecha_inicio, $p->fecha_fin]);
            $query->where(function ($q) use ($rangos) {
                foreach ($rangos as [$inicio, $fin]) {
                    $q->whereNotBetween('created_at', [$inicio, $fin]);
                }
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
        // Bloquear acceso al visor
        if (auth()->user()->rol === 'visor') {
            abort(403, 'Los visores no tienen permisos para eliminar incidencias.');
        }
        
        // Verificar permisos de eliminación
        $user = Auth::user();
        $rolesQueVenTodas = ['administrador', 'director_administrativo', 'técnico', 'auxiliar', 'asistente_postgrado'];
        
        if (!in_array($user->rol, $rolesQueVenTodas) && $incidencia->user_id !== $user->id) {
            abort(403, 'No tienes permisos para eliminar esta incidencia.');
        }

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





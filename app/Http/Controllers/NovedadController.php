<?php

namespace App\Http\Controllers;

use App\Models\Novedad;
use App\Models\Magister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class NovedadController extends Controller
{
    /**
     * Mostrar listado de novedades
     */
    public function index(Request $request)
    {
        try {
            $query = Novedad::with(['magister', 'user']);

            // Filtro por tipo
            if ($request->filled('tipo')) {
                $query->porTipo($request->tipo);
            }

            // Filtro por estado (activa/expirada)
            if ($request->filled('estado')) {
                if ($request->estado === 'activa') {
                    $query->activas();
                } elseif ($request->estado === 'expirada') {
                    $query->where('fecha_expiracion', '<=', now());
                }
            }

            // Filtro por visibilidad
            if ($request->filled('visibilidad')) {
                if ($request->visibilidad === 'publica') {
                    $query->where('visible_publico', true);
                } elseif ($request->visibilidad === 'privada') {
                    $query->where('visible_publico', false);
                }
            }

            // Búsqueda
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('titulo', 'like', '%' . $request->search . '%')
                      ->orWhere('contenido', 'like', '%' . $request->search . '%');
                });
            }

            $novedades = $query->orderBy('es_urgente', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString();

            $magisters = Magister::orderBy('nombre')->get();

            // Estadísticas calculadas sobre toda la tabla (no solo paginación)
            $estadisticas = [
                'total' => Novedad::count(),
                'publicas' => Novedad::where('visible_publico', true)->count(),
                'urgentes' => Novedad::where('es_urgente', true)->count(),
                'expiradas' => Novedad::where('fecha_expiracion', '<', now())->whereNotNull('fecha_expiracion')->count(),
            ];

            return view('novedades.index', compact('novedades', 'magisters', 'estadisticas'));

        } catch (Exception $e) {
            Log::error('Error al cargar novedades: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las novedades.');
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        try {
            $magisters = Magister::orderBy('nombre')->get();
            
            $tiposNovedad = [
                'academica' => 'Académica',
                'evento' => 'Evento',
                'admision' => 'Admisión',
                'institucional' => 'Institucional',
                'administrativa' => 'Administrativa',
                'sistema' => 'Sistema',
                'oportunidad' => 'Oportunidad',
                'servicio' => 'Servicio',
                'mantenimiento' => 'Mantenimiento'
            ];

            $rolesDisponibles = [
                'administrador' => 'Administrador',
                'docente' => 'Docente',
                'director_programa' => 'Director de Programa',
                'director_administrativo' => 'Director Administrativo',
                'asistente_programa' => 'Asistente de Programa',
                'asistente_postgrado' => 'Asistente de Postgrado',
                'técnico' => 'Técnico',
                'auxiliar' => 'Auxiliar'
            ];

            return view('novedades.create', compact('magisters', 'tiposNovedad', 'rolesDisponibles'));

        } catch (Exception $e) {
            Log::error('Error al cargar formulario de creación: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'Error al cargar el formulario.');
        }
    }

    /**
     * Almacenar nueva novedad
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'contenido' => 'required|string',
                'tipo_novedad' => 'required|string|max:50',
                'magister_id' => 'nullable|exists:magisters,id',
                'visible_publico' => 'boolean',
                'es_urgente' => 'boolean',
                'color' => 'nullable|string|max:20',
                'icono' => 'nullable|string|max:10',
                'fecha_expiracion' => 'nullable|date|after:today',
                'roles_visibles' => 'nullable|array'
            ]);

            // Asignar valores por defecto
            $validated['user_id'] = Auth::id();
            $validated['visible_publico'] = $request->has('visible_publico');
            $validated['es_urgente'] = $request->has('es_urgente');

            // Si es visible públicamente, no necesita roles específicos
            if ($validated['visible_publico']) {
                $validated['roles_visibles'] = null;
            }

            $novedad = Novedad::create($validated);

            Log::info('Novedad creada', [
                'novedad_id' => $novedad->id,
                'titulo' => $novedad->titulo,
                'user_id' => Auth::id()
            ]);

            return redirect()
                ->route('novedades.index')
                ->with('success', 'Novedad "' . $novedad->titulo . '" creada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al crear novedad: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la novedad. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Mostrar detalle de novedad
     */
    public function show(Novedad $novedad)
    {
        try {
            $novedad->load(['magister', 'user']);
            
            return view('novedades.show', compact('novedad'));

        } catch (Exception $e) {
            Log::error('Error al mostrar novedad: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'Error al cargar la novedad.');
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Novedad $novedad)
    {
        try {
            $magisters = Magister::orderBy('nombre')->get();
            
            $tiposNovedad = [
                'academica' => 'Académica',
                'evento' => 'Evento',
                'admision' => 'Admisión',
                'institucional' => 'Institucional',
                'administrativa' => 'Administrativa',
                'sistema' => 'Sistema',
                'oportunidad' => 'Oportunidad',
                'servicio' => 'Servicio',
                'mantenimiento' => 'Mantenimiento'
            ];

            $rolesDisponibles = [
                'administrador' => 'Administrador',
                'docente' => 'Docente',
                'director_programa' => 'Director de Programa',
                'director_administrativo' => 'Director Administrativo',
                'asistente_programa' => 'Asistente de Programa',
                'asistente_postgrado' => 'Asistente de Postgrado',
                'técnico' => 'Técnico',
                'auxiliar' => 'Auxiliar'
            ];

            return view('novedades.edit', compact('novedad', 'magisters', 'tiposNovedad', 'rolesDisponibles'));

        } catch (Exception $e) {
            Log::error('Error al cargar formulario de edición: ' . $e->getMessage());
            return redirect()->route('novedades.index')->with('error', 'Error al cargar el formulario de edición.');
        }
    }

    /**
     * Actualizar novedad
     */
    public function update(Request $request, Novedad $novedad)
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'contenido' => 'required|string',
                'tipo_novedad' => 'required|string|max:50',
                'magister_id' => 'nullable|exists:magisters,id',
                'visible_publico' => 'boolean',
                'es_urgente' => 'boolean',
                'color' => 'nullable|string|max:20',
                'icono' => 'nullable|string|max:10',
                'fecha_expiracion' => 'nullable|date',
                'roles_visibles' => 'nullable|array'
            ]);

            $validated['visible_publico'] = $request->has('visible_publico');
            $validated['es_urgente'] = $request->has('es_urgente');

            // Si es visible públicamente, limpiar roles específicos
            if ($validated['visible_publico']) {
                $validated['roles_visibles'] = null;
            }

            $tituloAnterior = $novedad->titulo;
            $novedad->update($validated);

            Log::info('Novedad actualizada', [
                'novedad_id' => $novedad->id,
                'titulo_anterior' => $tituloAnterior,
                'titulo_nuevo' => $novedad->titulo,
                'user_id' => Auth::id()
            ]);

            return redirect()
                ->route('novedades.index')
                ->with('success', 'Novedad "' . $novedad->titulo . '" actualizada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al actualizar novedad: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la novedad. Por favor, inténtelo nuevamente.');
        }
    }

    /**
     * Eliminar novedad
     */
    public function destroy($id)
    {
        try {
            // Buscar la novedad directamente por ID
            $novedad = Novedad::findOrFail($id);
            
            $titulo = $novedad->titulo;
            $novedadId = $novedad->id;
            
            $novedad->delete();

            Log::info('Novedad eliminada', [
                'novedad_id' => $novedadId,
                'titulo' => $titulo,
                'user_id' => Auth::id()
            ]);

            return redirect()
                ->route('novedades.index')
                ->with('success', 'Novedad "' . $titulo . '" eliminada correctamente.');

        } catch (Exception $e) {
            Log::error('Error al eliminar novedad: ' . $e->getMessage(), [
                'novedad_id' => $id ?? null,
                'user_id' => Auth::id()
            ]);
            return redirect()->back()
                ->with('error', 'Error al eliminar la novedad.');
        }
    }

    /**
     * Clonar una novedad existente
     */
    public function duplicate(Novedad $novedad)
    {
        try {
            $nueva = $novedad->replicate();
            $nueva->titulo = $novedad->titulo . ' (Copia)';
            $nueva->user_id = Auth::id();
            $nueva->created_at = now();
            $nueva->save();

            Log::info('Novedad duplicada', [
                'original_id' => $novedad->id,
                'nueva_id' => $nueva->id,
                'user_id' => Auth::id()
            ]);

            return redirect()
                ->route('novedades.edit', $nueva)
                ->with('success', 'Novedad duplicada. Puedes editarla ahora.');

        } catch (Exception $e) {
            Log::error('Error al duplicar novedad: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al duplicar la novedad.');
        }
    }
}


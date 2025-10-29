<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
use App\Models\Magister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NovedadController extends Controller
{
    /**
     * Listar novedades con filtros y paginación
     */
    public function index(Request $request)
    {
        $query = Novedad::with(['user', 'magister']);

        // Filtros opcionales
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->search . '%')
                  ->orWhere('contenido', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('magister_id')) {
            $query->where('magister_id', $request->magister_id);
        }

        if ($request->filled('es_urgente')) {
            $query->where('es_urgente', $request->boolean('es_urgente'));
        }

        // Filtro por estado (activa/expirada)
        if ($request->filled('estado')) {
            if ($request->estado === 'activa') {
                $query->where(function($q) {
                    $q->whereNull('fecha_expiracion')
                      ->orWhere('fecha_expiracion', '>', now());
                });
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

        // Solo novedades activas (no expiradas) - por defecto
        if ($request->get('only_active', false) && !$request->filled('estado')) {
            $query->where(function($q) {
                $q->whereNull('fecha_expiracion')
                  ->orWhere('fecha_expiracion', '>', now());
            });
        }

        $perPage = $request->get('per_page', 15);
        $novedades = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $novedades,
            'message' => 'Novedades obtenidas exitosamente'
        ]);
    }

    /**
     * Mostrar una novedad específica
     */
    public function show($id)
    {
        $novedad = Novedad::with(['user', 'magister'])->find($id);

        if (!$novedad) {
            return response()->json([
                'success' => false,
                'message' => 'Novedad no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $novedad,
            'message' => 'Novedad obtenida exitosamente'
        ]);
    }

    /**
     * Crear nueva novedad
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'tipo' => 'required|string|max:100',
            'color' => 'required|in:blue,green,yellow,red,purple',
            'icono' => 'required|in:info,warning,check,calendar,alert',
            'es_urgente' => 'nullable|boolean',
            'magister_id' => 'nullable|exists:magisters,id',
            'fecha_expiracion' => 'nullable|date|after:now',
            'acciones' => 'nullable|json',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'contenido.required' => 'El contenido es obligatorio.',
            'tipo.required' => 'El tipo de novedad es obligatorio.',
            'color.required' => 'Debe seleccionar un color.',
            'color.in' => 'El color seleccionado no es válido.',
            'icono.required' => 'Debe seleccionar un icono.',
            'icono.in' => 'El icono seleccionado no es válido.',
            'fecha_expiracion.after' => 'La fecha de expiración debe ser futura.',
        ]);

        $novedad = Novedad::create([
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
            'tipo' => $validated['tipo'],
            'color' => $validated['color'],
            'icono' => $validated['icono'],
            'es_urgente' => $validated['es_urgente'] ?? false,
            'magister_id' => $validated['magister_id'] ?? null,
            'fecha_expiracion' => $validated['fecha_expiracion'] ?? null,
            'acciones' => $validated['acciones'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Novedad creada correctamente.',
            'data' => $novedad->load(['user', 'magister'])
        ], 201);
    }

    /**
     * Actualizar novedad
     */
    public function update(Request $request, $id)
    {
        $novedad = Novedad::find($id);

        if (!$novedad) {
            return response()->json([
                'success' => false,
                'message' => 'Novedad no encontrada'
            ], 404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'tipo' => 'required|string|max:100',
            'color' => 'required|in:blue,green,yellow,red,purple',
            'icono' => 'required|in:info,warning,check,calendar,alert',
            'es_urgente' => 'nullable|boolean',
            'magister_id' => 'nullable|exists:magisters,id',
            'fecha_expiracion' => 'nullable|date',
            'acciones' => 'nullable|json',
        ]);

        $novedad->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Novedad actualizada correctamente.',
            'data' => $novedad->load(['user', 'magister'])
        ]);
    }

    /**
     * Eliminar novedad
     */
    public function destroy($id)
    {
        $novedad = Novedad::find($id);

        if (!$novedad) {
            return response()->json([
                'success' => false,
                'message' => 'Novedad no encontrada'
            ], 404);
        }

        $novedad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Novedad eliminada correctamente.'
        ]);
    }

    /**
     * Obtener novedades activas (no expiradas) con filtros
     */
    public function active(Request $request)
    {
        $query = Novedad::with(['user', 'magister'])
            ->where(function($q) {
                $q->whereNull('fecha_expiracion')
                  ->orWhere('fecha_expiracion', '>', now());
            });

        // Filtro por búsqueda de texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('contenido', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por magíster
        if ($request->filled('magister_id')) {
            $query->where('magister_id', $request->magister_id);
        }

        $novedades = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $novedades,
            'message' => 'Novedades activas obtenidas exitosamente',
            'filters_applied' => [
                'search' => $request->get('search'),
                'tipo' => $request->get('tipo'),
                'magister_id' => $request->get('magister_id'),
            ]
        ]);
    }

    /**
     * Obtener recursos para formularios
     */
    public function resources()
    {
        $magisters = Magister::select('id', 'nombre', 'color')->orderBy('nombre')->get();
        
        $tipos = [
            'Información General',
            'Evento Académico',
            'Cambio Administrativo',
            'Anuncio Importante',
            'Otro'
        ];

        $colores = [
            ['value' => 'blue', 'label' => 'Azul', 'hex' => '#3B82F6'],
            ['value' => 'green', 'label' => 'Verde', 'hex' => '#10B981'],
            ['value' => 'yellow', 'label' => 'Amarillo', 'hex' => '#F59E0B'],
            ['value' => 'red', 'label' => 'Rojo', 'hex' => '#EF4444'],
            ['value' => 'purple', 'label' => 'Morado', 'hex' => '#8B5CF6'],
        ];

        $iconos = [
            ['value' => 'info', 'label' => 'Información'],
            ['value' => 'warning', 'label' => 'Advertencia'],
            ['value' => 'check', 'label' => 'Confirmación'],
            ['value' => 'calendar', 'label' => 'Calendario'],
            ['value' => 'alert', 'label' => 'Alerta'],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'magisters' => $magisters,
                'tipos' => $tipos,
                'colores' => $colores,
                'iconos' => $iconos
            ],
            'message' => 'Recursos obtenidos exitosamente'
        ]);
    }

    /**
     * Estadísticas de novedades
     */
    public function statistics()
    {
        $stats = [
            'total' => Novedad::count(),
            'active' => Novedad::where(function($q) {
                    $q->whereNull('fecha_expiracion')
                      ->orWhere('fecha_expiracion', '>', now());
                })->count(),
            'urgent' => Novedad::where('es_urgente', true)->count(),
            'by_type' => Novedad::select('tipo', \DB::raw('count(*) as count'))
                ->groupBy('tipo')
                ->get()
                ->pluck('count', 'tipo'),
            'by_color' => Novedad::select('color', \DB::raw('count(*) as count'))
                ->groupBy('color')
                ->get()
                ->pluck('count', 'color'),
            'recent' => Novedad::with(['user:id,name', 'magister:id,nombre'])
                ->latest()
                ->limit(5)
                ->get(['id', 'titulo', 'tipo', 'color', 'es_urgente', 'user_id', 'magister_id', 'created_at']),
            'this_month' => Novedad::whereMonth('created_at', now()->month)->count(),
            'this_week' => Novedad::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Estadísticas de novedades obtenidas exitosamente'
        ]);
    }
}



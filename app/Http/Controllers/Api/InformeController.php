<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informe;
use App\Models\Magister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InformeController extends Controller
{
    /**
     * Listar informes con filtros y paginación
     */
    public function index(Request $request)
    {
        $query = Informe::with(['user', 'magister']);

        // Filtros opcionales
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%'.$request->search.'%')
                    ->orWhere('tipo', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('magister_id')) {
            $query->where('magister_id', $request->magister_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $perPage = $request->get('per_page', 15);
        $informes = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $informes,
            'message' => 'Informes obtenidos exitosamente',
        ]);
    }

    /**
     * Mostrar un informe específico
     */
    public function show($id)
    {
        $informe = Informe::with(['user', 'magister'])->find($id);

        if (! $informe) {
            return response()->json([
                'success' => false,
                'message' => 'Informe no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $informe,
            'message' => 'Informe obtenido exitosamente',
        ]);
    }

    /**
     * Crear nuevo informe
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'archivo' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            'magister_id' => 'nullable|exists:magisters,id',
        ], [
            'nombre.required' => 'El nombre del informe es obligatorio.',
            'tipo.required' => 'El tipo de informe es obligatorio.',
            'archivo.required' => 'Debe subir un archivo.',
            'archivo.mimes' => 'El archivo debe ser PDF, Word, Excel, PowerPoint o imagen.',
            'archivo.max' => 'El archivo no puede superar los 10MB.',
        ]);

        // Guardar archivo
        $file = $request->file('archivo');
        $path = $file->store('informes', 'public');

        $informe = Informe::create([
            'nombre' => $validated['nombre'],
            'tipo' => $validated['tipo'],
            'mime' => $file->getClientMimeType(),
            'archivo' => $path,
            'user_id' => Auth::id(),
            'magister_id' => $validated['magister_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Informe creado correctamente.',
            'data' => $informe->load(['user', 'magister']),
        ], 201);
    }

    /**
     * Actualizar informe
     */
    public function update(Request $request, $id)
    {
        $informe = Informe::find($id);

        if (! $informe) {
            return response()->json([
                'success' => false,
                'message' => 'Informe no encontrado',
            ], 404);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'archivo' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
            'magister_id' => 'nullable|exists:magisters,id',
        ]);

        // Actualizar archivo si se sube uno nuevo
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior
            if (Storage::disk('public')->exists($informe->archivo)) {
                Storage::disk('public')->delete($informe->archivo);
            }

            $file = $request->file('archivo');
            $path = $file->store('informes', 'public');
            $informe->archivo = $path;
            $informe->mime = $file->getClientMimeType();
        }

        $informe->nombre = $validated['nombre'];
        $informe->tipo = $validated['tipo'];
        $informe->magister_id = $validated['magister_id'] ?? null;
        $informe->save();

        return response()->json([
            'success' => true,
            'message' => 'Informe actualizado correctamente.',
            'data' => $informe->load(['user', 'magister']),
        ]);
    }

    /**
     * Eliminar informe
     */
    public function destroy($id)
    {
        $informe = Informe::find($id);

        if (! $informe) {
            return response()->json([
                'success' => false,
                'message' => 'Informe no encontrado',
            ], 404);
        }

        // Eliminar archivo del storage
        if (Storage::disk('public')->exists($informe->archivo)) {
            Storage::disk('public')->delete($informe->archivo);
        }

        $informe->delete();

        return response()->json([
            'success' => true,
            'message' => 'Informe eliminado correctamente.',
        ]);
    }

    /**
     * Descargar informe
     */
    public function download($id)
    {
        $informe = Informe::find($id);

        if (! $informe) {
            return response()->json([
                'success' => false,
                'message' => 'Informe no encontrado',
            ], 404);
        }

        if (! Storage::disk('public')->exists($informe->archivo)) {
            return response()->json([
                'success' => false,
                'message' => 'El archivo no existe en el servidor',
            ], 404);
        }

        return response()->download(
            storage_path('app/public/'.$informe->archivo),
            $informe->nombre.'.'.pathinfo($informe->archivo, PATHINFO_EXTENSION)
        );
    }

    /**
     * Obtener recursos para formularios (magisters, tipos, etc.)
     */
    public function resources()
{
    $magisters = Magister::select('id', 'nombre', 'color')
        ->orderBy('nombre')
        ->get()
        ->map(function($m) {
            return [
                'id' => $m->id,
                'name' => $m->nombre,  // ← IMPORTANTE: 'name', no 'nombre'
                'color' => $m->color
            ];
        });
    
    $users = User::select('id', 'name')->orderBy('name')->get();

    $tipos = [
        'calendario',
        'academico',
        'administrativo',
        'general'
    ];

    return response()->json([
        'magisters' => $magisters,
        'users' => $users,
        'tipos' => $tipos
    ]);
}

    /**
     * Estadísticas de informes
     */
    public function statistics()
    {
        $stats = [
            'total' => Informe::count(),
            'by_type' => Informe::select('tipo', \DB::raw('count(*) as count'))
                ->groupBy('tipo')
                ->get()
                ->pluck('count', 'tipo'),
            'by_magister' => Informe::with('magister:id,nombre')
                ->select('magister_id', \DB::raw('count(*) as count'))
                ->whereNotNull('magister_id')
                ->groupBy('magister_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'magister' => $item->magister->nombre ?? 'N/A',
                        'count' => $item->count,
                    ];
                }),
            'recent' => Informe::with(['user:id,name', 'magister:id,nombre'])
                ->latest()
                ->limit(5)
                ->get(['id', 'nombre', 'tipo', 'user_id', 'magister_id', 'created_at']),
            'this_month' => Informe::whereMonth('created_at', now()->month)->count(),
            'this_week' => Informe::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Estadísticas de informes obtenidas exitosamente',
        ]);
    }

    /**
     * ===== MÉTODO PÚBLICO (SIN AUTENTICACIÓN) =====
     * Obtener informes públicos para la app móvil
     */
    public function publicIndex(Request $request)
    {
        try {
            $query = Informe::with(['user:id,name', 'magister:id,nombre']);

            // Filtro por búsqueda de texto
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('descripcion', 'like', "%{$search}%");
                });
            }

            // Filtro por tipo
            if ($request->filled('tipo')) {
                $query->where('tipo', $request->tipo);
            }

            // Filtro por magister
            if ($request->filled('magister_id')) {
                $query->where('magister_id', $request->magister_id);
            }

            // Filtro por usuario
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Solo informes públicos (si tienes un campo public_view)
            // $query->where('public_view', true);

            $perPage = $request->get('per_page', 15);
            $informes = $query->latest()->paginate($perPage);

            // Formatear datos para respuesta pública
            $formattedInformes = $informes->map(function ($informe) {
                return [
                    'id' => $informe->id,
                    'titulo' => $informe->nombre,
                    'tipo' => $informe->tipo,
                    'archivo' => $informe->archivo,
                    'fechaCreacion' => $informe->created_at->format('Y-m-d H:i:s'),
                    'fechaActualizacion' => $informe->updated_at->format('Y-m-d H:i:s'),
                    'magisterId' => $informe->magister_id,
                    'magisterNombre' => $informe->magister ? $informe->magister->nombre : null,
                    'publicView' => true,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'current_page' => $informes->currentPage(),
                    'data' => $formattedInformes,
                    'first_page_url' => $informes->url(1),
                    'from' => $informes->firstItem(),
                    'last_page' => $informes->lastPage(),
                    'last_page_url' => $informes->url($informes->lastPage()),
                    'next_page_url' => $informes->nextPageUrl(),
                    'path' => $informes->path(),
                    'per_page' => $informes->perPage(),
                    'prev_page_url' => $informes->previousPageUrl(),
                    'to' => $informes->lastItem(),
                    'total' => $informes->total(),
                ],
                'message' => 'Informes públicos obtenidos exitosamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cargar los informes: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener un informe público específico
     */
    public function publicShow($id)
    {
        try {
            $informe = Informe::with(['user:id,name', 'magister:id,nombre'])->find($id);

            if (! $informe) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Informe no encontrado',
                ], 404);
            }

            $formattedInforme = [
                'id' => $informe->id,
                'titulo' => $informe->nombre,
                'tipo' => $informe->tipo,
                'archivo' => $informe->archivo,
                'fechaCreacion' => $informe->created_at->format('Y-m-d H:i:s'),
                'fechaActualizacion' => $informe->updated_at->format('Y-m-d H:i:s'),
                'magisterId' => $informe->magister_id,
                'magisterNombre' => $informe->magister ? $informe->magister->nombre : null,
                'publicView' => true,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $formattedInforme,
                'message' => 'Informe obtenido exitosamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cargar el informe: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Descargar informe público
     */
    public function publicDownload($id)
    {
        try {
            $informe = Informe::find($id);

            if (! $informe) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Informe no encontrado',
                ], 404);
            }

            if (! Storage::disk('public')->exists($informe->archivo)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El archivo no existe en el servidor',
                ], 404);
            }

            // Retornar el archivo para descarga
            return Storage::disk('public')->download(
                $informe->archivo,
                $informe->nombre.'.'.pathinfo($informe->archivo, PATHINFO_EXTENSION)
            );

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al descargar el informe: '.$e->getMessage(),
            ], 500);
        }
    }
}

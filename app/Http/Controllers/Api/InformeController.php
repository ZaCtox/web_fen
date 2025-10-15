<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informe;
use App\Models\Magister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            $query->where(function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('tipo', 'like', '%' . $request->search . '%');
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
            'message' => 'Informes obtenidos exitosamente'
        ]);
    }

    /**
     * Mostrar un informe específico
     */
    public function show($id)
    {
        $informe = Informe::with(['user', 'magister'])->find($id);

        if (!$informe) {
            return response()->json([
                'success' => false,
                'message' => 'Informe no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $informe,
            'message' => 'Informe obtenido exitosamente'
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
            'data' => $informe->load(['user', 'magister'])
        ], 201);
    }

    /**
     * Actualizar informe
     */
    public function update(Request $request, $id)
    {
        $informe = Informe::find($id);

        if (!$informe) {
            return response()->json([
                'success' => false,
                'message' => 'Informe no encontrado'
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
            'data' => $informe->load(['user', 'magister'])
        ]);
    }

    /**
     * Eliminar informe
     */
    public function destroy($id)
    {
        $informe = Informe::find($id);

        if (!$informe) {
            return response()->json([
                'success' => false,
                'message' => 'Informe no encontrado'
            ], 404);
        }

        // Eliminar archivo del storage
        if (Storage::disk('public')->exists($informe->archivo)) {
            Storage::disk('public')->delete($informe->archivo);
        }

        $informe->delete();

        return response()->json([
            'success' => true,
            'message' => 'Informe eliminado correctamente.'
        ]);
    }

    /**
     * Descargar informe
     */
    public function download($id)
    {
        $informe = Informe::find($id);

        if (!$informe) {
            return response()->json([
                'success' => false,
                'message' => 'Informe no encontrado'
            ], 404);
        }

        if (!Storage::disk('public')->exists($informe->archivo)) {
            return response()->json([
                'success' => false,
                'message' => 'El archivo no existe en el servidor'
            ], 404);
        }

        return response()->download(
            storage_path('app/public/' . $informe->archivo),
            $informe->nombre . '.' . pathinfo($informe->archivo, PATHINFO_EXTENSION)
        );
    }

    /**
     * Obtener recursos para formularios (magisters, tipos, etc.)
     */
    public function resources()
    {
        $magisters = Magister::select('id', 'nombre', 'color')->orderBy('nombre')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();
        
        $tipos = [
            'Informe Académico',
            'Reglamento',
            'Acta',
            'Documento Administrativo',
            'Presentación',
            'Otro'
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'magisters' => $magisters,
                'users' => $users,
                'tipos' => $tipos
            ],
            'message' => 'Recursos obtenidos exitosamente'
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
                ->map(function($item) {
                    return [
                        'magister' => $item->magister->nombre ?? 'N/A',
                        'count' => $item->count
                    ];
                }),
            'recent' => Informe::with(['user:id,name', 'magister:id,nombre'])
                ->latest()
                ->limit(5)
                ->get(['id', 'nombre', 'tipo', 'user_id', 'magister_id', 'created_at']),
            'this_month' => Informe::whereMonth('created_at', now()->month)->count(),
            'this_week' => Informe::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Estadísticas de informes obtenidas exitosamente'
        ]);
    }
}


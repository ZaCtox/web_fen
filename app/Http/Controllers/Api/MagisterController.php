<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Magister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MagisterController extends Controller
{
    public function index(Request $request)
    {
        $magisters = Magister::query()
            ->withCount('courses')
            ->when(
                $request->filled('q'),
                fn ($q) => $q->where('nombre', 'like', '%'.$request->q.'%')
            )
            ->orderBy('nombre')
            ->paginate(10);

        return response()->json($magisters, 200);
    }

    public function show(Magister $magister)
    {
        $magister->load(['courses.period']);

        return response()->json($magister, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string',
            'encargado' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
        ]);

        // Asignar automáticamente el siguiente número de orden
        if (!isset($validated['orden'])) {
            $maxOrden = Magister::max('orden') ?? 0;
            $validated['orden'] = $maxOrden + 1;
        }

        $magister = Magister::create($validated);

        return response()->json([
            'message' => 'Programa creado correctamente.',
            'magister' => $magister,
        ], 201);
    }

    public function update(Request $request, Magister $magister)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string',
            'encargado' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
        ]);

        $magister->update($validated);

        return response()->json([
            'message' => 'Programa actualizado.',
            'magister' => $magister,
        ], 200);
    }

    public function destroy(Magister $magister)
    {
        DB::transaction(function () use ($magister) {
            if ($magister->courses()->exists()) {
                $magister->courses()->delete();
            }
            $magister->delete();
        });

        return response()->json([
            'message' => 'Programa y cursos asociados eliminados.',
        ], 200);
    }

    // ===== MÉTODO PÚBLICO (SIN AUTENTICACIÓN) =====
    public function publicIndex(Request $request)
    {
        $magisters = Magister::select('id', 'nombre', 'encargado', 'color')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $magisters,
        ]);
    }

    public function publicMagistersWithCourseCount(Request $request)
    {
        try {
            $query = Magister::select('id', 'nombre', 'encargado', 'color')
                ->withCount('courses')
                ->orderBy('nombre');

            // Aplicar búsqueda si se proporciona
            if ($request->filled('search')) {
                $query->where('nombre', 'like', '%' . $request->search . '%');
            }

            // Aplicar paginación
            $perPage = $request->get('per_page', 50);
            $page = $request->get('page', 1);
            
            $magisters = $query->paginate($perPage, ['*'], 'page', $page);

            // Formatear datos para respuesta pública
            $formattedMagisters = $magisters->map(function ($magister) {
                return [
                    'id' => $magister->id,
                    'nombre' => $magister->nombre,
                    'encargado' => $magister->encargado,
                    'color' => $magister->color,
                    'courses_count' => $magister->courses_count,
                    'telefono' => null, // No incluir datos sensibles
                    'correo' => null,   // No incluir datos sensibles
                    'is_active' => true,
                    'public_view' => true,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $formattedMagisters,
                'meta' => [
                    'current_page' => $magisters->currentPage(),
                    'last_page' => $magisters->lastPage(),
                    'per_page' => $magisters->perPage(),
                    'total' => $magisters->total(),
                    'has_more_pages' => $magisters->hasMorePages(),
                    'public_view' => true,
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en publicMagistersWithCourseCount: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Error al cargar los magísteres: ' . $e->getMessage(),
            ], 500);
        }
    }
}

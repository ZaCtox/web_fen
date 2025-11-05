<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cloudinary\Api\Upload\UploadApi;
use Exception;

class StaffController extends Controller
{
    // Obtener todos los registros
    public function index(Request $request)
    {
        $query = Staff::query();

        // Filtros opcionales
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                    ->orWhere('cargo', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('cargo')) {
            $query->where('cargo', 'like', '%' . $request->cargo . '%');
        }

        $perPage = $request->get('per_page', 15);
        $staff = $query->orderBy('nombre')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $staff,
            'message' => 'Staff obtenido exitosamente'
        ]);
    }

    // Obtener un registro específico
    public function show($id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json(['message' => 'Miembro no encontrado'], 404);
        }

        return response()->json($staff);
    }

    // Crear nuevo miembro (CON CLOUDINARY)
    public function store(Request $request)
    {
        // Verificar permisos: solo director_administrativo y decano pueden crear
        if (!in_array(auth()->user()->rol, ['director_administrativo', 'decano'])) {
            return response()->json([
                'message' => 'No tienes permisos para crear miembros del equipo',
            ], 403);
        }

        try {
            // Validación
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:staff,email',
                'cargo' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'anexo' => 'nullable|string|max:10',
                'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048', // 2MB max
                'avatar_color' => 'nullable|string|max:6',
            ]);

            // Manejar la subida de la foto a Cloudinary
            if ($request->hasFile('foto')) {
                try {
                    $cloudinaryUpload = (new UploadApi)->upload(
                        $request->file('foto')->getRealPath(),
                        ['folder' => 'staff']
                    );
                    $validated['foto'] = $cloudinaryUpload['secure_url'];
                    $validated['public_id'] = $cloudinaryUpload['public_id'];
                } catch (Exception $e) {
                    Log::error('Error al subir foto a Cloudinary: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al subir la foto. Por favor, inténtelo nuevamente.',
                    ], 500);
                }
            }

            // Procesar color del avatar (quitar el # si viene)
            if (isset($validated['avatar_color']) && $validated['avatar_color']) {
                $color = $validated['avatar_color'];
                if (str_starts_with($color, '#')) {
                    $validated['avatar_color'] = substr($color, 1);
                }
            }

            $staff = Staff::create($validated);

            Log::info('Nuevo personal creado desde API', ['staff_id' => $staff->id, 'nombre' => $staff->nombre]);

            return response()->json([
                'success' => true,
                'message' => 'Miembro creado correctamente.',
                'data' => $staff,
            ], 201);
        } catch (Exception $e) {
            Log::error('Error al crear personal desde API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el miembro del personal: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Actualizar miembro (CON CLOUDINARY)
    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'Miembro no encontrado'
            ], 404);
        }

        // Verificar permisos: solo director_administrativo y decano pueden actualizar
        if (!in_array(auth()->user()->rol, ['director_administrativo', 'decano'])) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para actualizar miembros del equipo',
            ], 403);
        }

        try {
            // Validación
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:staff,email,' . $id,
                'cargo' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'anexo' => 'nullable|string|max:10',
                'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048', // 2MB max
                'avatar_color' => 'nullable|string|max:6',
            ]);

            // Manejar la subida de nueva foto a Cloudinary
            if ($request->hasFile('foto')) {
                try {
                    // Eliminar la foto anterior de Cloudinary si existe
                    if ($staff->public_id) {
                        try {
                            (new UploadApi)->destroy($staff->public_id);
                        } catch (Exception $e) {
                            Log::warning('No se pudo eliminar foto anterior de Cloudinary: ' . $e->getMessage());
                        }
                    }
                    
                    // Subir nueva foto a Cloudinary
                    $cloudinaryUpload = (new UploadApi)->upload(
                        $request->file('foto')->getRealPath(),
                        ['folder' => 'staff']
                    );
                    $validated['foto'] = $cloudinaryUpload['secure_url'];
                    $validated['public_id'] = $cloudinaryUpload['public_id'];
                } catch (Exception $e) {
                    Log::error('Error al subir foto a Cloudinary: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al subir la foto. Por favor, inténtelo nuevamente.',
                    ], 500);
                }
            }

            // Procesar color del avatar (quitar el # si viene)
            if (isset($validated['avatar_color']) && $validated['avatar_color']) {
                $color = $validated['avatar_color'];
                if (str_starts_with($color, '#')) {
                    $validated['avatar_color'] = substr($color, 1);
                }
            }

            $nombreAnterior = $staff->nombre;
            $staff->update($validated);

            Log::info('Personal actualizado desde API', [
                'staff_id' => $staff->id,
                'nombre_anterior' => $nombreAnterior,
                'nombre_nuevo' => $staff->nombre
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Miembro actualizado correctamente.',
                'data' => $staff,
            ]);
        } catch (Exception $e) {
            Log::error('Error al actualizar personal desde API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el miembro: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Eliminar miembro (CON CLOUDINARY)
    public function destroy($id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'Miembro no encontrado'
            ], 404);
        }

        // Verificar permisos: solo director_administrativo y decano pueden eliminar
        if (!in_array(auth()->user()->rol, ['director_administrativo', 'decano'])) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para eliminar miembros del equipo',
            ], 403);
        }

        try {
            $nombre = $staff->nombre;
            
            // Eliminar la foto de Cloudinary si existe
            if ($staff->public_id) {
                try {
                    (new UploadApi)->destroy($staff->public_id);
                } catch (Exception $e) {
                    Log::warning('No se pudo eliminar foto de Cloudinary: ' . $e->getMessage());
                }
            }
            
            $staff->delete();

            Log::info('Personal eliminado desde API', ['nombre' => $nombre]);

            return response()->json([
                'success' => true,
                'message' => 'Miembro eliminado correctamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al eliminar personal desde API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el miembro: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ===== MÉTODO PÚBLICO (SIN AUTENTICACIÓN) =====
    public function publicIndex()
    {
        try {
            // Obtener staff para vista pública
            $staff = Staff::select('id', 'nombre', 'cargo', 'telefono', 'anexo', 'email', 'foto', 'avatar_color', 'public_id')
                ->orderBy('cargo')
                ->orderBy('nombre')
                ->get();

            // Formatear datos para respuesta pública
            $formattedStaff = $staff->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->nombre,
                    'role' => $member->cargo,
                    'email' => $member->email,
                    'phone' => $member->telefono,
                    'anexo' => $member->anexo,
                    'foto' => $member->foto,
                    'avatar_color' => $member->avatar_color,
                    'department' => $member->cargo, // Usar cargo como department
                    'public_view' => true,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $formattedStaff,
                'meta' => [
                    'total' => $formattedStaff->count(),
                    'public_view' => true,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cargar el equipo: ' . $e->getMessage(),
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Listar usuarios con filtros y paginación
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros opcionales
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('rol', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        $perPage = $request->get('per_page', 15);
        $users = $query->select('id', 'name', 'email', 'rol', 'foto', 'avatar_color', 'created_at', 'last_login_at')
            ->orderBy('name')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Usuarios obtenidos exitosamente'
        ]);
    }

    /**
     * Mostrar un usuario específico
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Usuario obtenido exitosamente'
        ]);
    }

    /**
     * Crear nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string|in:administrador,director_administrativo,director_programa,asistente_programa,docente,técnico,auxiliar,asistente_postgrado',
            'avatar_color' => 'nullable|string|max:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol' => $validated['rol'],
            'avatar_color' => $validated['avatar_color'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente.',
            'data' => $user
        ], 201);
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {

        $user = User::find($id);

        // No permitir editar el propio usuario
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes editar tu propio usuario'
            ], 400);
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'rol' => 'sometimes|required|string|in:administrador,director_administrativo,director_programa,asistente_programa,docente,técnico,auxiliar,asistente_postgrado',
            'avatar_color' => 'sometimes|nullable|string|max:6',
        ]);

        // Solo actualizar password si se proporciona
        if (isset($validated['password']) && $validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente.',
            'data' => $user
        ]);
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        $user = User::find($id);

        // No permitir eliminar el propio usuario
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu propio usuario'
            ], 400);
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // No permitir eliminar el último administrador
        if ($user->rol === 'administrador') {
            $adminCount = User::where('rol', 'administrador')->count();
            if ($adminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el último administrador del sistema'
                ], 400);
            }
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente.'
        ]);
    }

    /**
     * Obtener estadísticas de usuarios
     */
    public function statistics()
    {
        $stats = [
            'total' => User::count(),
            'by_role' => User::select('rol', \DB::raw('count(*) as count'))
                ->groupBy('rol')
                ->get()
                ->pluck('count', 'rol'),
            'recent_users' => User::latest()->limit(5)->get(['id', 'name', 'email', 'rol', 'created_at']),
            'this_month' => User::whereMonth('created_at', now()->month)->count(),
            'this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Estadísticas de usuarios obtenidas exitosamente'
        ]);
    }
}
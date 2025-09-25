<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // Obtener todos los registros
    public function index()
    {
        $staff = Staff::orderBy('nombre')->get(['id', 'nombre', 'cargo', 'telefono', 'email']);

        return response()->json($staff);
    }

    // Obtener un registro específico
    public function show($id)
    {
        $staff = Staff::find($id);

        if (! $staff) {
            return response()->json(['message' => 'Miembro no encontrado'], 404);
        }

        return response()->json($staff);
    }

    // Crear nuevo miembro
    public function store(StaffRequest $request)
    {
        $staff = Staff::create($request->validated());

        return response()->json([
            'message' => 'Miembro creado correctamente.',
            'data' => $staff,
        ], 201);
    }

    // Actualizar miembro
    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);

        if (! $staff) {
            return response()->json(['message' => 'Miembro no encontrado'], 404);
        }

        // Validación manual excluyendo el ID actual
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,'.$id,
            'cargo' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $staff->update($request->all());

        return response()->json([
            'message' => 'Miembro actualizado correctamente.',
            'data' => $staff,
        ]);
    }

    // Eliminar miembro
    public function destroy($id)
    {
        $staff = Staff::find($id);

        if (! $staff) {
            return response()->json(['message' => 'Miembro no encontrado'], 404);
        }

        $staff->delete();

        return response()->json(['message' => 'Miembro eliminado correctamente.']);
    }

    // ===== MÉTODO PÚBLICO (SIN AUTENTICACIÓN) =====
    public function publicIndex()
    {
        try {
            // Obtener staff para vista pública (sin filtro de 'activo' ya que no existe)
            $staff = Staff::select('id', 'nombre', 'cargo', 'telefono', 'email')
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
                    'department' => $member->cargo, // Usando cargo como departamento
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
                'message' => 'Error al cargar el equipo: '.$e->getMessage(),
            ], 500);
        }
    }
}

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

        if (!$staff) {
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
            'data' => $staff
        ], 201);
    }

    // Actualizar miembro
    public function update(StaffRequest $request, $id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json(['message' => 'Miembro no encontrado'], 404);
        }

        $staff->update($request->validated());

        return response()->json([
            'message' => 'Miembro actualizado correctamente.',
            'data' => $staff
        ]);
    }

    // Eliminar miembro
    public function destroy($id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json(['message' => 'Miembro no encontrado'], 404);
        }

        $staff->delete();

        return response()->json(['message' => 'Miembro eliminado correctamente.']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmergencyController extends Controller
{
    /**
     * Listar emergencias (paginadas).
     */
    public function index()
    {
        $emergencies = Emergency::latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $emergencies
        ]);
    }

    /**
     * Crear nueva emergencia (solo una activa).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string',
        ]);

        // Desactivar todas las demás emergencias activas
        Emergency::where('active', true)->update(['active' => false]);

        $emergency = Emergency::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'active' => true,
            'expires_at' => Carbon::now()->addHours(24),
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Emergencia creada.',
            'data' => $emergency
        ], 201);
    }

    /**
     * Actualizar emergencia.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string',
        ]);

        $emergency = Emergency::findOrFail($id);
        $emergency->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Emergencia actualizada.',
            'data' => $emergency
        ]);
    }

    /**
     * Desactivar emergencia.
     */
    public function deactivate($id)
    {
        $emergency = Emergency::findOrFail($id);
        $emergency->update(['active' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'Emergencia desactivada manualmente.',
            'data' => $emergency
        ]);
    }

    /**
     * Eliminar emergencia.
     */
    public function destroy($id)
    {
        $emergency = Emergency::findOrFail($id);
        $emergency->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Emergencia eliminada.'
        ]);
    }

    /**
     * Consultar la emergencia activa (si existe y no está expirada).
     */
    public function active()
    {
        $emergency = Emergency::where('active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $emergency
        ]);
    }
}

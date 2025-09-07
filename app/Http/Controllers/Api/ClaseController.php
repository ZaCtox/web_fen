<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    /**
     * Listar todas las clases (con curso, magíster, período y sala).
     */
    public function index(Request $request)
    {
        $filters = $request->only('magister', 'sala', 'dia');

        $clases = Clase::with(['course.magister', 'period', 'room'])
            ->filtrar($filters)
            ->orderBy('period_id')
            ->orderByRaw("FIELD(dia, 'Viernes','Sábado')")
            ->orderBy('hora_inicio')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $clases
        ]);
    }

    /**
     * Crear nueva clase.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'tipo' => 'required|string|max:50',
            'period_id' => 'required|exists:periods,id',
            'room_id' => 'nullable|exists:rooms,id',
            'modality' => 'required|string|in:online,presencial,mixto',
            'dia' => 'required|in:Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'url_zoom' => 'nullable|url',
            'encargado' => 'nullable|string|max:255'
        ]);

        $clase = Clase::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Clase creada correctamente.',
            'data' => $clase->load(['course.magister', 'period', 'room'])
        ], 201);
    }

    /**
     * Mostrar una clase específica.
     */
    public function show(Clase $clase)
    {
        return response()->json([
            'status' => 'success',
            'data' => $clase->load(['course.magister', 'period', 'room'])
        ]);
    }

    /**
     * Actualizar clase.
     */
    public function update(Request $request, Clase $clase)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'tipo' => 'required|string|max:50',
            'period_id' => 'required|exists:periods,id',
            'room_id' => 'nullable|exists:rooms,id',
            'modality' => 'required|string|in:online,presencial,mixto',
            'dia' => 'required|in:Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'url_zoom' => 'nullable|url',
            'encargado' => 'nullable|string|max:255'
        ]);

        $clase->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Clase actualizada correctamente.',
            'data' => $clase->load(['course.magister', 'period', 'room'])
        ]);
    }

    /**
     * Eliminar clase.
     */
    public function destroy(Clase $clase)
    {
        $clase->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Clase eliminada correctamente.'
        ]);
    }
}

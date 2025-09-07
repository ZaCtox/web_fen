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
                fn($q) => $q->where('nombre', 'like', '%' . $request->q . '%')
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
            'nombre'    => 'required|string|max:255',
            'color'     => 'nullable|string',
            'encargado' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'correo'    => 'nullable|email|max:255',
        ]);

        $magister = Magister::create($validated);

        return response()->json([
            'message'  => 'Programa creado correctamente.',
            'magister' => $magister
        ], 201);
    }

    public function update(Request $request, Magister $magister)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',
            'color'     => 'nullable|string',
            'encargado' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'correo'    => 'nullable|email|max:255',
        ]);

        $magister->update($validated);

        return response()->json([
            'message'  => 'Programa actualizado.',
            'magister' => $magister
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
            'message' => 'Programa y cursos asociados eliminados.'
        ], 200);
    }
}

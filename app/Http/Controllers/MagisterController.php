<?php

namespace App\Http\Controllers;

use App\Models\Magister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreMagisterRequest;
use App\Http\Requests\UpdateMagisterRequest;

class MagisterController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAccess();

        $magisters = Magister::query()
            ->withCount('courses')
            ->when(
                $request->filled('q'),
                fn($q) => $q->where('nombre', 'like', '%' . $request->q . '%')
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('magisters.index', compact('magisters'));
    }

    public function create()
    {
        $this->authorizeAccess();
        return view('magisters.create');
    }

    public function store(StoreMagisterRequest $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string',
            'encargado' => 'nullable|string|max:255',
            'asistente' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'anexo' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
        ]);

        Magister::create($validated);

        return redirect()
            ->route('magisters.index')
            ->with('success', 'Programa creado correctamente.');
    }

    public function edit(Magister $magister)
    {
        $this->authorizeAccess();
        return view('magisters.edit', compact('magister'));
    }

    public function update(UpdateMagisterRequest $request, Magister $magister)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'nullable|string',
            'encargado' => 'nullable|string|max:255',
            'asistente' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'anexo' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
        ]);

        $magister->update($validated);

        return redirect()
            ->route('magisters.index')
            ->with('success', 'Programa actualizado.');
    }

    public function destroy(Magister $magister)
    {
        $this->authorizeAccess();

        DB::transaction(function () use ($magister) {
            if ($magister->courses()->exists()) {
                $magister->courses()->delete();
            }
            $magister->delete();
        });

        return redirect()
            ->route('magisters.index')
            ->with('success', 'Programa y cursos asociados eliminados.');
    }

    private function authorizeAccess(): void
    {
        if (!tieneRol(['administrativo', 'docente'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Magister;
use Illuminate\Http\Request;

class MagisterController extends Controller
{
    public function index()
    {
        $this->authorizeAccess();
        $magisters = Magister::withCount('courses')->get();
        return view('magisters.index', compact('magisters'));
    }

    public function create()
    {
        $this->authorizeAccess();
        return view('magisters.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();
        $request->validate([
            'nombre' => 'required|string|unique:magisters,nombre|max:255'
        ]);

        Magister::create(['nombre' => $request->nombre]);

        return redirect()->route('magisters.index')->with('success', 'Magíster creado correctamente.');
    }

    public function edit(Magister $magister)
    {
        $this->authorizeAccess();
        return view('magisters.edit', compact('magister'));
    }

    public function update(Request $request, Magister $magister)
    {
        $this->authorizeAccess();
        $request->validate([
            'nombre' => 'required|string|max:255|unique:magisters,nombre,' . $magister->id
        ]);

        $magister->update(['nombre' => $request->nombre]);

        return redirect()->route('magisters.index')->with('success', 'Magíster actualizado.');
    }

    public function destroy(Magister $magister)
    {
        $this->authorizeAccess();

        // Eliminar cursos asociados manualmente antes de eliminar el magíster
        if ($magister->courses()->exists()) {
            // Eliminar todos los cursos primero
            $magister->courses()->delete();
        }

        $magister->delete();

        return redirect()->route('magisters.index')->with('success', 'Magíster y cursos asociados eliminados.');
    }


    private function authorizeAccess()
    {
        if (!in_array(auth()->user()->rol, ['administrativo', 'docente'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }
}

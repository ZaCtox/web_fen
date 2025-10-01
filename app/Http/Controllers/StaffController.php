<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $staff = Staff::query()
            ->orderBy('nombre')
<<<<<<< Updated upstream
            ->get(['id', 'nombre', 'cargo', 'telefono', 'email']);
=======
            ->get(['id', 'nombre', 'cargo', 'telefono','anexo', 'email']);
>>>>>>> Stashed changes

        return view('staff.index', compact('staff'));
    }

    public function show(Staff $staff)
    {
        return view('staff.show', compact('staff'));
    }

    public function create()
    {
        $this->authorizeAccess();
        return view('staff.create');
    }

    public function store(StaffRequest $request)
    {
        $this->authorizeAccess();
        Staff::create($request->validated());

        return redirect()
            ->route('staff.index')
            ->with('success', 'Miembro creado correctamente.');
    }

    public function edit(Staff $staff)
    {
        $this->authorizeAccess();
        return view('staff.edit', compact('staff'));
    }

    public function update(StaffRequest $request, Staff $staff)
    {
        $this->authorizeAccess();
        $staff->update($request->validated());

        return redirect()
            ->route('staff.index')
            ->with('success', 'Miembro actualizado.');
    }

    public function destroy(Staff $staff)
    {
        $this->authorizeAccess();
        $staff->delete();

        return redirect()
            ->route('staff.index')
            ->with('success', 'Miembro eliminado.');
    }

    private function authorizeAccess()
    {
        if (!tieneRol(['administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
    }
}

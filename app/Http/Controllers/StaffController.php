<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // Si quieres restringir: $this->middleware('role:administrativo')->except(['index','show']);

public function index(Request $request)
{
    // Trae todo. Para aligerar, selecciona solo las columnas que usas.
    $staff = Staff::query()
        ->orderBy('nombre')
        ->get(['id','nombre','cargo','telefono','email']);

    // Ya no necesitamos $q ni paginate()
    return view('staff.index', compact('staff'));
}



    public function create()
    {
        return view('staff.create');
    }

    public function store(StaffRequest $request)
    {
        Staff::create($request->validated());
        return redirect()->route('staff.index')->with('ok', 'Miembro creado correctamente.');
    }

    public function show(Staff $staff)
    {
        return view('staff.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(StaffRequest $request, Staff $staff)
    {
        $staff->update($request->validated());
        return redirect()->route('staff.index')->with('ok', 'Miembro actualizado.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('ok', 'Miembro eliminado.');
    }
}

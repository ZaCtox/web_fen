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
        $q = $request->get('q');
        $staff = Staff::query()
            ->when($q, fn($qr) => $qr->where(function($w) use ($q) {
                $w->where('nombre','like',"%$q%")
                  ->orWhere('cargo','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            }))
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('staff.index', compact('staff','q'));
    }

    public function create() { return view('staff.create'); }

    public function store(StaffRequest $request)
    {
        Staff::create($request->validated());
        return redirect()->route('staff.index')->with('ok','Miembro creado correctamente.');
    }

    public function show(Staff $staff) { return view('staff.show', compact('staff')); }

    public function edit(Staff $staff) { return view('staff.edit', compact('staff')); }

    public function update(StaffRequest $request, Staff $staff)
    {
        $staff->update($request->validated());
        return redirect()->route('staff.index')->with('ok','Miembro actualizado.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('ok','Miembro eliminado.');
    }
}

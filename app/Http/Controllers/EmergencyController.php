<?php

namespace App\Http\Controllers;

use App\Models\Emergency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmergencyController extends Controller
{
    public function index()
    {
        $emergencies = Emergency::latest()->paginate(10);
        return view('emergencies.index', compact('emergencies'));
    }

    public function create()
    {
        return view('emergencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string',
        ]);

        // Desactivar todas las demás emergencias
        Emergency::where('active', true)->update(['active' => false]);

        Emergency::create([
            'title' => $request->title,
            'message' => $request->message,
            'active' => true,
            'expires_at' => Carbon::now()->addHours(24), // o minutes para probar
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('emergencies.index')->with('success', 'Emergencia creada.');
    }

    public function show($id)
    {
        $emergency = Emergency::findOrFail($id);
        return view('emergencies.show', compact('emergency'));
    }

    public function edit($id)
    {
        $emergency = Emergency::findOrFail($id);
        return view('emergencies.edit', compact('emergency'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string',
        ]);

        $emergency = Emergency::findOrFail($id);
        $emergency->update($request->only(['title', 'message']));

        return redirect()->route('emergencies.index')->with('success', 'Emergencia actualizada.');
    }

    public function deactivate($id)
    {
        $emergency = Emergency::findOrFail($id);
        $emergency->update(['active' => false]);

        return redirect()->route('emergencies.index')->with('success', 'Emergencia desactivada manualmente.');
    }

    public function toggleActive($id)
    {
        $emergency = Emergency::findOrFail($id);
        
        if ($emergency->active) {
            // Desactivar
            $emergency->update(['active' => false]);
            $message = 'Emergencia desactivada.';
        } else {
            // Activar - desactivar todas las demás primero
            Emergency::where('active', true)->update(['active' => false]);
            $emergency->update(['active' => true]);
            $message = 'Emergencia activada.';
        }

        return back()->with('success', $message);
    }

    public function destroy($id)
    {
        Emergency::findOrFail($id)->delete();
        return redirect()->route('emergencies.index')->with('success', 'Emergencia eliminada.');
    }

    public function active()
    {
        return Emergency::where('active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();
    }

}

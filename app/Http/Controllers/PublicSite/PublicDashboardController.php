<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Emergency;
use Illuminate\Support\Facades\Auth; // ⚠️ importante para Auth::check()

class PublicDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Redirige a dashboard si está logueado
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Emergencia más reciente
        $emergency = Emergency::latest()->first();

        // Datos de tu página pública, por ejemplo:
        $rooms = \App\Models\Room::orderBy('name')->get();
        $magisters = \App\Models\Magister::with(['courses.period'])->orderBy('nombre')->get();

        return view('public.dashboard', compact('rooms', 'magisters', 'emergency'));
    }
}

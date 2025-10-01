<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\Room;
use App\Models\Event;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $resumen = [
            'incidencias' => Incident::count(),
            'salas' => Room::count(),
            'eventos' => Event::count(),
            'usuarios' => User::count(),
        ];

        $ultimas = Incident::latest()->take(5)->get();

        return view('dashboard', compact('resumen', 'ultimas', 'user'));
    }
}

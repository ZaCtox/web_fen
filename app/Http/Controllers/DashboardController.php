<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\Room;
use App\Models\Event;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Verificación de rol (opcional, según tu sistema)
        if (!in_array(Auth::user()->rol, ['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }

        // Resumen de datos
        $resumen = [
            'incidencias' => Incidencia::count(),
            'salas' => Room::count(),
            'eventos' => Event::count(),
            'usuarios' => User::count(),
        ];

        // Obtener últimas incidencias si aún quieres mostrarlas
        $ultimas = Incidencia::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact('resumen', 'ultimas'));
    }
}

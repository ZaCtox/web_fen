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
<<<<<<< Updated upstream
    private function authorizeAccess()
=======
<<<<<<< Updated upstream
    public function index()
>>>>>>> Stashed changes
    {
        if (!tieneRol(['docente', 'administrativo'])) {
            abort(403, 'Acceso no autorizado.');
        }
<<<<<<< Updated upstream
    }

    public function index()
    {
        $this->authorizeAccess();

        $user = Auth::user();
=======
=======
    // Eliminamos authorizeAccess completamente

    public function index()
    {
        // Quitamos la llamada a $this->authorizeAccess();

        $user = Auth::user();
>>>>>>> Stashed changes
>>>>>>> Stashed changes

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

<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class PublicRoomController extends Controller
{
<<<<<<< Updated upstream
    public function index(Request $request)
=======
    public function index()
>>>>>>> Stashed changes
    {
        $rooms = Room::orderBy('name')->get();
        return view('public.rooms', compact('rooms'));
    }
<<<<<<< Updated upstream
=======

    public function show(Room $room)
    {
        // Solo enviamos la sala a la vista de ficha
        return view('rooms.partials.ficha', [
            'room'  => $room,
            'public' => true, // si quieres manejar estilos o tabs públicos
        ]);
    }
>>>>>>> Stashed changes
}

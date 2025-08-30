<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class PublicRoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::orderBy('name')->get();
        return view('public.rooms', compact('rooms'));
    }
}

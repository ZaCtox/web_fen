<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Event;
use App\Models\Magister;
use App\Models\Room;
use App\Models\RoomUsage;
use App\Models\Notification; // <- Asegúrate de tener este modelo
use Illuminate\Http\Request;
use App\Models\Emergency;


class GuestDashboardController extends Controller
{

public function index()
{
    $rooms = Room::orderBy('name')->get();
    $magisters = Magister::with(['courses.period'])->orderBy('nombre')->get();

    $emergency = Emergency::where('active', true)->latest()->first();

    return view('public.dashboard', compact('rooms', 'magisters', 'emergency'));
}

}

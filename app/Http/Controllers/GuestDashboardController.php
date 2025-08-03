<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Event;
use App\Models\Magister;
use App\Models\Room;
use App\Models\RoomUsage;

class GuestDashboardController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('name')->get();
        $magisters = Magister::with(['courses.period'])->orderBy('nombre')->get();

        return view('guest_dashboard', compact('rooms', 'magisters'));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(
            Event::with(['room', 'course', 'period'])->get()
        );
    }
}

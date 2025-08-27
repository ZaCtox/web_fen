<?php

namespace App\Http\Controllers;

use App\Models\Emergency;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string',
        ]);

        Emergency::create([
            'title' => $request->title,
            'message' => $request->message,
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'Emergencia creada.');
    }

    public function active()
    {
        return Emergency::where('active', true)->latest()->first();
    }
}

<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    //
    public function index()
    {
        $salas = Room::orderBy('name')->get();
        return view('public.index', compact('salas'));
    }

}


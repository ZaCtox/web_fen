<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;

class IncidenciaController extends Controller
{
    public function index()
    {
        return response()->json(Incident::all());
    }
}

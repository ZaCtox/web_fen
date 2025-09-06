<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Http\Resources\ClaseResource;

class ClaseController extends Controller
{
    public function index()
    {
        $clases = Clase::with(['course.magister', 'period', 'room'])->get();

        // Devuelve un array plano en lugar del objeto con "data"
        return response()->json(ClaseResource::collection($clases)->resolve());
    }
}

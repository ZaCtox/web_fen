<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Magister;
use Illuminate\Http\Request;

class MagisterController extends Controller
{
    public function index()
    {
        // Trae todos los magíster con sus datos
        $magisters = Magister::all(['id', 'nombre', 'color', 'encargado', 'telefono', 'correo']);
        return response()->json($magisters);
    }

    public function show($id)
    {
        $magister = Magister::with('courses')->findOrFail($id);
        return response()->json($magister);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Magister;

class MagisterController extends Controller
{
    public function index()
    {
        return response()->json(Magister::all());
    }
}

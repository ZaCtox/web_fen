<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class PublicStaffController extends Controller
{
public function index(Request $request)
{
    // Trae todo. Para aligerar, selecciona solo las columnas que usas.
    $staff = Staff::query()
        ->orderBy('nombre')
        ->get(['id','nombre','cargo','telefono','email']);

    // Ya no necesitamos $q ni paginate()
    return view('public.staff-index', compact('staff'));
}
}
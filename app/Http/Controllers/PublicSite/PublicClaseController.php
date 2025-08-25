<?php
namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Clase;

class PublicClaseController extends Controller
{
    public function show(Clase $clase)
    {
        // Reutiliza la misma vista "show" pasando un flag $public = true
        return view('clases.show', [
            'clase'  => $clase,
            'public' => true,
        ]);
    }
}

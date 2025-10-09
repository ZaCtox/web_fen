<?php
namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Clase;

class PublicClaseController extends Controller
{
    public function show(Clase $clase)
    {
        // Cargar relaciones necesarias incluyendo sesiones
        $clase->load(['course.magister', 'period', 'room', 'sesiones' => function($query) {
            $query->where('estado', 'completada')
                  ->whereNotNull('url_grabacion')
                  ->orderBy('fecha', 'desc');
        }]);
        
        // Reutiliza la misma vista "show" pasando un flag $public = true
        return view('clases.show', [
            'clase'  => $clase,
            'public' => true,
        ]);
    }
}

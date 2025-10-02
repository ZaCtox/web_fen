<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Informe;
use Illuminate\Support\Facades\Storage;

class PublicInformeController extends Controller
{
    /**
     * Mostrar lista de informes públicos
     */
    public function index()
    {
        // Traer todos los informes con usuario y magister
        $informes = Informe::with('magister', 'user')->latest()->get();

        return view('public.informes', compact('informes'));
    }

    /**
     * Descargar informe público
     */
    public function download($id)
    {
        $informe = Informe::findOrFail($id);

        // Verificar si el archivo existe
        $path = $informe->archivo; // ruta relativa en storage/app/public/informes/xxx
        if (!Storage::disk('public')->exists($path)) {
            return redirect()->back()->with('error', 'Archivo no encontrado');
        }

        // Descargar usando Storage
        $mime = $informe->mime ?: 'application/octet-stream';
        $filename = $informe->nombre . '.' . pathinfo($path, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($path, $filename, [
            'Content-Type' => $mime
        ]);
    }
}

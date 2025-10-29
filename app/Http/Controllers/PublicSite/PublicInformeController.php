<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Informe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicInformeController extends Controller
{
    /**
     * Mostrar lista de informes públicos
     */
    public function index(Request $request)
    {
        $query = Informe::with('magister', 'user');

        // Filtro por búsqueda de texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por magister
        if ($request->filled('magister_id')) {
            $query->where('magister_id', $request->magister_id);
        }

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $informes = $query->latest()->get();
        $magisters = \App\Models\Magister::orderBy('nombre')->get();
        $users = \App\Models\User::orderBy('name')->get();

        $tipos = [
            'calendario' => 'Calendario',
            'academico' => 'Académico',
            'administrativo' => 'Administrativo',
            'general' => 'General'
        ];

        return view('public.informes', compact('informes', 'magisters', 'users', 'tipos'));
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


<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use App\Models\Magister;
use App\Http\Requests\InformeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformeController extends Controller
{
    public function index()
    {
        $informes = Informe::with('user', 'magister')->latest()->get();
        $magisters = Magister::all();

        return view('informes.index', compact('informes', 'magisters'));
    }

    public function create()
    {
        $magisters = Magister::all();
        return view('informes.create', compact('magisters'));
    }

    public function store(InformeRequest $request)
    {
        $data = $request->validated();
        
        $file = $request->file('archivo');
        $path = $file->store('informes', 'public');

        Informe::create([
            'nombre' => $data['nombre'],
            'tipo' => $data['tipo'],
            'mime' => $file->getClientMimeType(),
            'archivo' => $path,
            'user_id' => auth()->id(),
            'magister_id' => $data['magister_id'],
        ]);

        return redirect()->route('informes.index')->with('success', 'Informe guardado correctamente');
    }

    public function edit($id)
    {
        $informe = Informe::findOrFail($id);
        $magisters = Magister::all();
        return view('informes.edit', compact('informe', 'magisters'));
    }

    public function update(InformeRequest $request, $id)
    {
        $informe = Informe::findOrFail($id);
        $data = $request->validated();

        // Reemplazar archivo si se sube uno nuevo
        if ($request->hasFile('archivo')) {
            if (Storage::disk('public')->exists($informe->archivo)) {
                Storage::disk('public')->delete($informe->archivo);
            }
            $file = $request->file('archivo');
            $path = $file->store('informes', 'public');
            $informe->archivo = $path;
            $informe->mime = $file->getClientMimeType();
        }

        $informe->nombre = $data['nombre'];
        $informe->tipo = $data['tipo'];
        $informe->magister_id = $data['magister_id'];
        $informe->save();

        return redirect()->route('informes.index')->with('success', 'Informe actualizado correctamente');
    }

    public function download($id)
    {
        $informe = Informe::findOrFail($id);

        if (!Storage::disk('public')->exists($informe->archivo)) {
            return redirect()->back()->with('error', 'Archivo no encontrado');
        }

        $extension = pathinfo($informe->archivo, PATHINFO_EXTENSION);
        $filename = $informe->nombre . '.' . $extension;

        return Storage::disk('public')->download($informe->archivo, $filename);
    }

    public function destroy($id)
    {
        $informe = Informe::findOrFail($id);

        if (Storage::disk('public')->exists($informe->archivo)) {
            Storage::disk('public')->delete($informe->archivo);
        }

        $informe->delete();

        return redirect()->route('informes.index')->with('success', 'Informe eliminado correctamente');
    }
}

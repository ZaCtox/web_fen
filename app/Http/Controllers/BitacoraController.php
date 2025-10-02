<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BitacoraController extends Controller
{
    public function index()
    {
        $bitacoras = Bitacora::with('room')->latest()->paginate(10);

        return view('bitacoras.index', compact('bitacoras'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('bitacoras.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lugar' => 'required|string|max:255',
            'room_id' => 'nullable|exists:rooms,id',
            'detalle_ubicacion' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'foto_url' => 'nullable|string', // desde Cloudinary
        ]);

        $bitacora = Bitacora::create($request->only('lugar', 'room_id', 'detalle_ubicacion', 'descripcion', 'foto_url'));

        $this->generarPDF($bitacora);

        return redirect()->route('bitacoras.index')->with('success', 'Bitácora creada con éxito');
    }

    public function show(Bitacora $bitacora)
    {
        return view('bitacoras.show', compact('bitacora'));
    }

    public function edit(Bitacora $bitacora)
    {
        $rooms = Room::all();
        return view('bitacoras.edit', compact('bitacora', 'rooms'));
    }

    public function update(Request $request, Bitacora $bitacora)
    {
        $request->validate([
            'lugar' => 'required|string|max:255',
            'room_id' => 'nullable|exists:rooms,id',
            'detalle_ubicacion' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'foto_url' => 'nullable|string',
        ]);

        $bitacora->update($request->only('lugar', 'room_id', 'detalle_ubicacion', 'descripcion', 'foto_url'));

        $this->generarPDF($bitacora);

        return redirect()->route('bitacoras.index')->with('success', 'Bitácora actualizada');
    }

    public function destroy(Bitacora $bitacora)
    {
        if ($bitacora->pdf_path) {
            Storage::disk('public')->delete($bitacora->pdf_path);
        }

        $bitacora->delete();

        return redirect()->route('bitacoras.index')->with('success', 'Eliminado correctamente');
    }

    public function download(Bitacora $bitacora)
    {
        if (! $bitacora->pdf_path) {
            return back()->with('error', 'No hay PDF disponible');
        }

        return Storage::disk('public')->download($bitacora->pdf_path);
    }

    /**
     * Generar y guardar PDF de la bitácora
     */
    private function generarPDF(Bitacora $bitacora)
    {
        $pdf = Pdf::loadView('bitacoras.pdf', compact('bitacora'));
        $path = 'bitacoras/' . uniqid() . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        $bitacora->update(['pdf_path' => $path]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BitacoraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Bitacora::with(['room', 'user'])->latest();

        // Filtros opcionales
        if ($request->filled('ubicacion')) {
            $query->where('lugar', $request->ubicacion);
        }

        if ($request->filled('sala')) {
            $query->where('room_id', $request->sala);
        }

        if ($request->filled('con_foto')) {
            $query->whereNotNull('foto_url');
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $bitacoras = $query->paginate(15);
        $rooms = Room::orderBy('name')->get();

        return view('bitacoras.index', compact('bitacoras', 'rooms'));
    }

    public function create()
    {
        $rooms = Room::orderBy('name')->get();
        return view('bitacoras.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'lugar' => 'required|string|in:Sala,Baño,Pasillo,Laboratorio,Oficina,Otro',
            'room_id' => 'nullable|required_if:lugar,Sala|exists:rooms,id',
            'detalle_ubicacion' => 'nullable|required_if:lugar,Baño,Pasillo,Laboratorio,Oficina,Otro|string|max:255',
            'descripcion' => 'required|string|min:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ], [
            'titulo.required' => 'El título del reporte es obligatorio.',
            'titulo.max' => 'El título no puede exceder los 255 caracteres.',
            'lugar.required' => 'Debe seleccionar un tipo de ubicación.',
            'lugar.in' => 'El tipo de ubicación seleccionado no es válido.',
            'room_id.required_if' => 'Debe seleccionar una sala cuando el tipo es "Sala".',
            'room_id.exists' => 'La sala seleccionada no existe.',
            'detalle_ubicacion.required_if' => 'Debe especificar el detalle de ubicación.',
            'detalle_ubicacion.max' => 'El detalle de ubicación no puede exceder los 255 caracteres.',
            'descripcion.required' => 'La descripción del reporte es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            'foto.image' => 'El archivo debe ser una imagen válida.',
            'foto.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'foto.max' => 'La imagen no puede exceder los 10MB.',
        ]);

        $data = $request->only('titulo', 'lugar', 'room_id', 'detalle_ubicacion', 'descripcion');
        $data['user_id'] = Auth::id();

        // Subir imagen a Cloudinary si se proporciona
        if ($request->hasFile('foto')) {
            try {
                $uploadedFileUrl = Cloudinary::upload($request->file('foto')->getRealPath())->getSecurePath();
                $data['foto_url'] = $uploadedFileUrl;
            } catch (\Exception $e) {
                return back()->withErrors(['foto' => 'Error al subir la imagen: ' . $e->getMessage()])->withInput();
            }
        }

        $bitacora = Bitacora::create($data);

        // Generar PDF
        $this->generarPDF($bitacora);

        return redirect()->route('bitacoras.index')
            ->with('success', 'Reporte de bitácora creado exitosamente.');
    }

    public function show(Bitacora $bitacora)
    {
        $bitacora->load(['room', 'user']);
        return view('bitacoras.show', compact('bitacora'));
    }

    public function edit(Bitacora $bitacora)
    {
        $rooms = Room::orderBy('name')->get();
        return view('bitacoras.edit', compact('bitacora', 'rooms'));
    }

    public function update(Request $request, Bitacora $bitacora)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'lugar' => 'required|string|in:Sala,Baño,Pasillo,Laboratorio,Oficina,Otro',
            'room_id' => 'nullable|required_if:lugar,Sala|exists:rooms,id',
            'detalle_ubicacion' => 'nullable|required_if:lugar,Baño,Pasillo,Laboratorio,Oficina,Otro|string|max:255',
            'descripcion' => 'required|string|min:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ], [
            'titulo.required' => 'El título del reporte es obligatorio.',
            'titulo.max' => 'El título no puede exceder los 255 caracteres.',
            'lugar.required' => 'Debe seleccionar un tipo de ubicación.',
            'lugar.in' => 'El tipo de ubicación seleccionado no es válido.',
            'room_id.required_if' => 'Debe seleccionar una sala cuando el tipo es "Sala".',
            'room_id.exists' => 'La sala seleccionada no existe.',
            'detalle_ubicacion.required_if' => 'Debe especificar el detalle de ubicación.',
            'detalle_ubicacion.max' => 'El detalle de ubicación no puede exceder los 255 caracteres.',
            'descripcion.required' => 'La descripción del reporte es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            'foto.image' => 'El archivo debe ser una imagen válida.',
            'foto.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'foto.max' => 'La imagen no puede exceder los 10MB.',
        ]);

        $data = $request->only('titulo', 'lugar', 'room_id', 'detalle_ubicacion', 'descripcion');

        // Subir nueva imagen a Cloudinary si se proporciona
        if ($request->hasFile('foto')) {
            try {
                $uploadedFileUrl = Cloudinary::upload($request->file('foto')->getRealPath())->getSecurePath();
                $data['foto_url'] = $uploadedFileUrl;
            } catch (\Exception $e) {
                return back()->withErrors(['foto' => 'Error al subir la imagen: ' . $e->getMessage()])->withInput();
            }
        }

        $bitacora->update($data);

        // Regenerar PDF
        $this->generarPDF($bitacora);

        return redirect()->route('bitacoras.index')
            ->with('success', 'Reporte de bitácora actualizado exitosamente.');
    }

    public function destroy(Bitacora $bitacora)
    {
        // Eliminar PDF del storage
        if ($bitacora->pdf_path && Storage::disk('public')->exists($bitacora->pdf_path)) {
            Storage::disk('public')->delete($bitacora->pdf_path);
        }

        $bitacora->delete();

        return redirect()->route('bitacoras.index')
            ->with('success', 'Reporte de bitácora eliminado exitosamente.');
    }

    public function download(Bitacora $bitacora)
    {
        if (!$bitacora->pdf_path || !Storage::disk('public')->exists($bitacora->pdf_path)) {
            return back()->with('error', 'No hay PDF disponible para descargar.');
        }

        $filename = 'reporte_bitacora_' . $bitacora->id . '_' . now()->format('Y-m-d') . '.pdf';
        
        return Storage::disk('public')->download($bitacora->pdf_path, $filename);
    }

    /**
     * Generar y guardar PDF de la bitácora
     */
    private function generarPDF(Bitacora $bitacora)
    {
        try {
            $bitacora->load(['room', 'user']);
            
            $pdf = Pdf::loadView('bitacoras.pdf', compact('bitacora'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

            $filename = 'bitacora_' . $bitacora->id . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            $path = 'bitacoras/' . $filename;
            
            Storage::disk('public')->put($path, $pdf->output());

            $bitacora->update(['pdf_path' => $path]);
            
        } catch (\Exception $e) {
            \Log::error('Error generando PDF para bitácora ' . $bitacora->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Exportar estadísticas de bitácoras
     */
    public function estadisticas()
    {
        $estadisticas = [
            'total' => Bitacora::count(),
            'por_ubicacion' => Bitacora::selectRaw('lugar, COUNT(*) as total')
                ->groupBy('lugar')
                ->get(),
            'con_foto' => Bitacora::whereNotNull('foto_url')->count(),
            'recientes' => Bitacora::recientes()->count(),
            'por_mes' => Bitacora::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, COUNT(*) as total')
                ->groupBy('mes')
                ->orderBy('mes', 'desc')
                ->limit(12)
                ->get(),
        ];

        return response()->json($estadisticas);
    }
}

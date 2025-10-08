<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\ReportEntry;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DailyReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = DailyReport::with(['user', 'entries'])->latest('report_date');

        // Filtros opcionales
        if ($request->filled('fecha_desde')) {
            $query->whereDate('report_date', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('report_date', '<=', $request->fecha_hasta);
        }

        if ($request->filled('usuario')) {
            $query->where('user_id', $request->usuario);
        }

        $reports = $query->paginate(10);

        return view('daily-reports.index', compact('reports'));
    }

    public function create()
    {
        $rooms = Room::orderBy('name')->get();
        $today = now()->format('Y-m-d');
        $tituloSugerido = DailyReport::generarTitulo();
        
        return view('daily-reports.create', compact('rooms', 'today', 'tituloSugerido'));
    }

    public function store(Request $request)
    {
        \Log::info('DailyReportController@store - Iniciando', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        try {
            $request->validate([
            'title' => 'required|string|max:255',
            'report_date' => 'required|date',
            'summary' => 'nullable|string',
            'entries' => 'required|array|min:1',
            'entries.*.location_type' => 'required|string|in:Sala,Baño,Pasillo,Laboratorio,Oficina,Otro',
            'entries.*.room_id' => 'nullable|required_if:entries.*.location_type,Sala|exists:rooms,id',
            'entries.*.location_detail' => 'nullable|required_if:entries.*.location_type,Baño,Pasillo,Laboratorio,Oficina,Otro|string|max:255',
            'entries.*.observation' => 'required|string|min:5',
            'entries.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ], [
            'title.required' => 'El título del reporte es obligatorio.',
            'report_date.required' => 'La fecha del reporte es obligatoria.',
            'entries.required' => 'Debe agregar al menos una entrada al reporte.',
            'entries.min' => 'Debe agregar al menos una entrada al reporte.',
            'entries.*.location_type.required' => 'Debe seleccionar el tipo de ubicación.',
            'entries.*.room_id.required_if' => 'Debe seleccionar una sala cuando el tipo es "Sala".',
            'entries.*.location_detail.required_if' => 'Debe especificar el detalle de ubicación.',
            'entries.*.observation.required' => 'La observación es obligatoria.',
            'entries.*.observation.min' => 'La observación debe tener al menos 5 caracteres.',
            'entries.*.photo.image' => 'El archivo debe ser una imagen válida.',
            'entries.*.photo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'entries.*.photo.max' => 'La imagen no puede exceder los 10MB.',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('DailyReportController@store - Error de validación', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('DailyReportController@store - Error inesperado', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Error inesperado: ' . $e->getMessage()])->withInput();
        }

        // Crear el reporte diario
        \Log::info('DailyReportController@store - Creando reporte diario', [
            'title' => $request->title,
            'report_date' => $request->report_date,
            'summary' => $request->summary,
            'user_id' => Auth::id()
        ]);

        $report = DailyReport::create([
            'title' => $request->title,
            'report_date' => $request->report_date,
            'summary' => $request->summary,
            'user_id' => Auth::id(),
        ]);

        \Log::info('DailyReportController@store - Reporte creado', [
            'report_id' => $report->id
        ]);

        // Procesar las entradas
        \Log::info('DailyReportController@store - Procesando entradas', [
            'entries_count' => count($request->entries)
        ]);

        foreach ($request->entries as $index => $entryData) {
            \Log::info('DailyReportController@store - Procesando entrada', [
                'index' => $index,
                'entry_data' => $entryData
            ]);

            $entry = [
                'daily_report_id' => $report->id,
                'location_type' => $entryData['location_type'],
                'room_id' => $entryData['room_id'] ?? null,
                'location_detail' => $entryData['location_detail'] ?? null,
                'observation' => $entryData['observation'],
                'order' => $index + 1,
            ];

            // Subir imagen si se proporciona
            if (isset($entryData['photo']) && $entryData['photo']) {
                try {
                    \Log::info('DailyReportController@store - Subiendo imagen', [
                        'index' => $index,
                        'file_name' => $entryData['photo']->getClientOriginalName()
                    ]);
                    
                    $uploadedFileUrl = Cloudinary::upload($entryData['photo']->getRealPath())->getSecurePath();
                    $entry['photo_url'] = $uploadedFileUrl;
                    
                    \Log::info('DailyReportController@store - Imagen subida exitosamente', [
                        'index' => $index,
                        'photo_url' => $uploadedFileUrl
                    ]);
                } catch (\Exception $e) {
                    \Log::error('DailyReportController@store - Error subiendo imagen', [
                        'index' => $index,
                        'error' => $e->getMessage()
                    ]);
                    return back()->withErrors(['entries.' . $index . '.photo' => 'Error al subir la imagen: ' . $e->getMessage()])->withInput();
                }
            }

            $createdEntry = ReportEntry::create($entry);
            \Log::info('DailyReportController@store - Entrada creada', [
                'index' => $index,
                'entry_id' => $createdEntry->id
            ]);
        }

        // Generar PDF
        \Log::info('DailyReportController@store - Generando PDF', [
            'report_id' => $report->id
        ]);
        $this->generarPDF($report);

        \Log::info('DailyReportController@store - Proceso completado exitosamente', [
            'report_id' => $report->id
        ]);

        return redirect()->route('daily-reports.index')
            ->with('success', 'Reporte diario creado exitosamente.');
    }

    public function show(DailyReport $dailyReport)
    {
        $dailyReport->load(['user', 'entries.room']);
        return view('daily-reports.show', compact('dailyReport'));
    }

    public function edit(DailyReport $dailyReport)
    {
        $rooms = Room::orderBy('name')->get();
        $dailyReport->load(['entries.room']);
        return view('daily-reports.edit', compact('dailyReport', 'rooms'));
    }

    public function update(Request $request, DailyReport $dailyReport)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'report_date' => 'required|date',
            'summary' => 'nullable|string',
            'entries' => 'required|array|min:1',
            'entries.*.location_type' => 'required|string|in:Sala,Baño,Pasillo,Laboratorio,Oficina,Otro',
            'entries.*.room_id' => 'nullable|required_if:entries.*.location_type,Sala|exists:rooms,id',
            'entries.*.location_detail' => 'nullable|required_if:entries.*.location_type,Baño,Pasillo,Laboratorio,Oficina,Otro|string|max:255',
            'entries.*.observation' => 'required|string|min:5',
            'entries.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Actualizar el reporte
        $dailyReport->update([
            'title' => $request->title,
            'report_date' => $request->report_date,
            'summary' => $request->summary,
        ]);

        // Eliminar entradas existentes
        $dailyReport->entries()->delete();

        // Crear nuevas entradas
        foreach ($request->entries as $index => $entryData) {
            $entry = [
                'daily_report_id' => $dailyReport->id,
                'location_type' => $entryData['location_type'],
                'room_id' => $entryData['room_id'] ?? null,
                'location_detail' => $entryData['location_detail'] ?? null,
                'observation' => $entryData['observation'],
                'order' => $index + 1,
            ];

            // Subir nueva imagen si se proporciona
            if (isset($entryData['photo']) && $entryData['photo']) {
                try {
                    $uploadedFileUrl = Cloudinary::upload($entryData['photo']->getRealPath())->getSecurePath();
                    $entry['photo_url'] = $uploadedFileUrl;
                } catch (\Exception $e) {
                    return back()->withErrors(['entries.' . $index . '.photo' => 'Error al subir la imagen: ' . $e->getMessage()])->withInput();
                }
            }

            ReportEntry::create($entry);
        }

        // Regenerar PDF
        $this->generarPDF($dailyReport);

        return redirect()->route('daily-reports.index')
            ->with('success', 'Reporte diario actualizado exitosamente.');
    }

    public function destroy(DailyReport $dailyReport)
    {
        // Eliminar PDF del storage
        if ($dailyReport->pdf_path && Storage::disk('public')->exists($dailyReport->pdf_path)) {
            Storage::disk('public')->delete($dailyReport->pdf_path);
        }

        $dailyReport->delete();

        return redirect()->route('daily-reports.index')
            ->with('success', 'Reporte diario eliminado exitosamente.');
    }

    public function download(DailyReport $dailyReport)
    {
        if (!$dailyReport->pdf_path || !Storage::disk('public')->exists($dailyReport->pdf_path)) {
            return back()->with('error', 'No hay PDF disponible para descargar.');
        }

        $filename = 'reporte_diario_' . $dailyReport->id . '_' . $dailyReport->report_date->format('Y-m-d') . '.pdf';
        
        return Storage::disk('public')->download($dailyReport->pdf_path, $filename);
    }

    /**
     * Generar y guardar PDF del reporte diario
     */
    private function generarPDF(DailyReport $dailyReport)
    {
        try {
            $dailyReport->load(['user', 'entries.room']);
            
            $report = $dailyReport;
            $pdf = Pdf::loadView('daily-reports.pdf', compact('report'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

            $filename = 'reporte_diario_' . $dailyReport->id . '_' . $dailyReport->report_date->format('Y-m-d_H-i-s') . '.pdf';
            $path = 'daily-reports/' . $filename;
            
            Storage::disk('public')->put($path, $pdf->output());

            $dailyReport->update(['pdf_path' => $path]);
            
        } catch (\Exception $e) {
            \Log::error('Error generando PDF para reporte diario ' . $dailyReport->id . ': ' . $e->getMessage());
        }
    }
}
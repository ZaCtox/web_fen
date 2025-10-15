<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\ReportEntry;
use App\Models\Room;
use App\Models\Magister;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DailyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DailyReport::with(['user', 'entries.room'])->latest('report_date');

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

        if ($request->filled('escala_min')) {
            $query->whereHas('entries', function($q) use ($request) {
                $q->where('escala', '>=', $request->escala_min);
            });
        }

        if ($request->filled('escala_max')) {
            $query->whereHas('entries', function($q) use ($request) {
                $q->where('escala', '<=', $request->escala_max);
            });
        }

        if ($request->filled('programa')) {
            $query->whereHas('entries', function($q) use ($request) {
                $q->where('programa', 'like', '%' . $request->programa . '%');
            });
        }

        if ($request->filled('area')) {
            $query->whereHas('entries', function($q) use ($request) {
                $q->where('area', 'like', '%' . $request->area . '%');
            });
        }

        $perPage = $request->get('per_page', 15);
        $reports = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $reports,
            'message' => 'Reportes diarios obtenidos exitosamente'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'report_date' => 'required|date',
                'summary' => 'nullable|string',
                'entries' => 'required|array|min:1',
                'entries.*.location_type' => 'required|in:Sala,Otra',
                'entries.*.room_id' => 'required_if:entries.*.location_type,Sala|nullable|exists:rooms,id',
                'entries.*.location_detail' => 'required_if:entries.*.location_type,Otra|nullable|string|max:255',
                'entries.*.observation' => 'required|string|min:5',
                'entries.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'entries.*.hora' => 'required|string|max:50',
                'entries.*.escala' => 'required|integer|min:1|max:10',
                'entries.*.programa' => 'required|string|max:255',
                'entries.*.area' => 'required|string|max:255',
                'entries.*.tarea' => 'nullable|string',
            ], [
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
                'entries.*.hora.required' => 'El horario es obligatorio.',
                'entries.*.escala.required' => 'Debe seleccionar un nivel de severidad.',
                'entries.*.escala.min' => 'La escala debe ser entre 1 y 10.',
                'entries.*.escala.max' => 'La escala debe ser entre 1 y 10.',
                'entries.*.programa.required' => 'El programa es obligatorio.',
                'entries.*.area.required' => 'El área es obligatoria.',
            ]);

            $report = DailyReport::create([
                'title' => $request->title,
                'report_date' => $request->report_date,
                'summary' => $request->summary,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->entries as $index => $entryData) {
                $entry = [
                    'daily_report_id' => $report->id,
                    'location_type' => $entryData['location_type'],
                    'room_id' => $entryData['room_id'] ?? null,
                    'location_detail' => $entryData['location_detail'] ?? null,
                    'observation' => $entryData['observation'],
                    'hora' => $entryData['hora'],
                    'escala' => $entryData['escala'],
                    'programa' => $entryData['programa'],
                    'area' => $entryData['area'],
                    'tarea' => $entryData['tarea'] ?? null,
                ];

                // Manejar imagen si existe
                if (isset($entryData['photo']) && $entryData['photo']) {
                    try {
                        $uploadedFileUrl = Cloudinary::upload($entryData['photo']->getRealPath())->getSecurePath();
                        $entry['photo_url'] = $uploadedFileUrl;
                    } catch (\Exception $e) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Error al subir la imagen: ' . $e->getMessage()
                        ], 422);
                    }
                }

                ReportEntry::create($entry);
            }

            // Generar PDF
            $this->generarPDF($report);

            return response()->json([
                'success' => true,
                'data' => $report->load(['user', 'entries.room']),
                'message' => 'Reporte diario creado exitosamente'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyReport $dailyReport)
    {
        $dailyReport->load(['user', 'entries.room']);
        
        return response()->json([
            'success' => true,
            'data' => $dailyReport,
            'message' => 'Reporte diario obtenido exitosamente'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyReport $dailyReport)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'report_date' => 'required|date',
                'summary' => 'nullable|string',
                'entries' => 'required|array|min:1',
                'entries.*.location_type' => 'required|in:Sala,Otra',
                'entries.*.room_id' => 'required_if:entries.*.location_type,Sala|nullable|exists:rooms,id',
                'entries.*.location_detail' => 'required_if:entries.*.location_type,Otra|nullable|string|max:255',
                'entries.*.observation' => 'required|string|min:5',
                'entries.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'entries.*.hora' => 'required|string|max:50',
                'entries.*.escala' => 'required|integer|min:1|max:10',
                'entries.*.programa' => 'required|string|max:255',
                'entries.*.area' => 'required|string|max:255',
                'entries.*.tarea' => 'nullable|string',
            ]);

            $dailyReport->update([
                'title' => $request->title,
                'report_date' => $request->report_date,
                'summary' => $request->summary,
            ]);

            // Eliminar entradas existentes
            $dailyReport->entries()->delete();

            // Crear nuevas entradas
            foreach ($request->entries as $entryData) {
                $entry = [
                    'daily_report_id' => $dailyReport->id,
                    'location_type' => $entryData['location_type'],
                    'room_id' => $entryData['room_id'] ?? null,
                    'location_detail' => $entryData['location_detail'] ?? null,
                    'observation' => $entryData['observation'],
                    'hora' => $entryData['hora'],
                    'escala' => $entryData['escala'],
                    'programa' => $entryData['programa'],
                    'area' => $entryData['area'],
                    'tarea' => $entryData['tarea'] ?? null,
                ];

                // Manejar imagen si existe
                if (isset($entryData['photo']) && $entryData['photo']) {
                    try {
                        $uploadedFileUrl = Cloudinary::upload($entryData['photo']->getRealPath())->getSecurePath();
                        $entry['photo_url'] = $uploadedFileUrl;
                    } catch (\Exception $e) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Error al subir la imagen: ' . $e->getMessage()
                        ], 422);
                    }
                }

                ReportEntry::create($entry);
            }

            // Regenerar PDF
            $this->generarPDF($dailyReport);

            return response()->json([
                'success' => true,
                'data' => $dailyReport->load(['user', 'entries.room']),
                'message' => 'Reporte diario actualizado exitosamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyReport $dailyReport)
    {
        try {
            // Eliminar PDF si existe
            $pdfPath = storage_path('app/public/daily-reports/' . $dailyReport->id . '.pdf');
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            $dailyReport->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reporte diario eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download PDF of the daily report
     */
    public function downloadPdf(DailyReport $dailyReport)
    {
        try {
            $pdfPath = storage_path('app/public/daily-reports/' . $dailyReport->id . '.pdf');
            
            if (!file_exists($pdfPath)) {
                $this->generarPDF($dailyReport);
            }

            return response()->download($pdfPath, 'reporte-diario-' . $dailyReport->id . '.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for daily reports
     */
    public function statistics(Request $request)
    {
        try {
            $query = DailyReport::with('entries');

            // Filtros de fecha
            if ($request->filled('fecha_desde')) {
                $query->whereDate('report_date', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('report_date', '<=', $request->fecha_hasta);
            }

            $reports = $query->get();

            // Estadísticas por escala de severidad
            $escalaStats = [];
            for ($i = 1; $i <= 10; $i++) {
                $count = $reports->sum(function($report) use ($i) {
                    return $report->entries->where('escala', $i)->count();
                });
                $escalaStats[$i] = $count;
            }

            // Estadísticas por programa
            $programaStats = $reports->flatMap(function($report) {
                return $report->entries->pluck('programa');
            })->countBy();

            // Estadísticas por área
            $areaStats = $reports->flatMap(function($report) {
                return $report->entries->pluck('area');
            })->countBy();

            // Total de reportes
            $totalReportes = $reports->count();

            // Total de entradas
            $totalEntradas = $reports->sum(function($report) {
                return $report->entries->count();
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'total_reportes' => $totalReportes,
                    'total_entradas' => $totalEntradas,
                    'escala_severidad' => $escalaStats,
                    'programas' => $programaStats,
                    'areas' => $areaStats,
                ],
                'message' => 'Estadísticas obtenidas exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get resources needed for creating/editing daily reports
     */
    public function resources()
    {
        try {
            $rooms = Room::orderBy('name')->get(['id', 'name']);
            $magisters = Magister::orderBy('nombre')->get(['id', 'nombre']);

            return response()->json([
                'success' => true,
                'data' => [
                    'rooms' => $rooms,
                    'magisters' => $magisters,
                ],
                'message' => 'Recursos obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF for daily report
     */
    private function generarPDF(DailyReport $report)
    {
        try {
            $report->load(['user', 'entries.room']);
            
            $pdf = Pdf::loadView('daily-reports.pdf', compact('report'));
            $pdf->setPaper('A4', 'portrait');
            
            // Crear directorio si no existe
            $directory = storage_path('app/public/daily-reports');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $pdf->save(storage_path('app/public/daily-reports/' . $report->id . '.pdf'));
            
        } catch (\Exception $e) {
            \Log::error('Error generando PDF para reporte diario', [
                'report_id' => $report->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}

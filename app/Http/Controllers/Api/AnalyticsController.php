<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Incident;
use App\Models\Course;
use App\Models\Clase;
use App\Models\DailyReport;
use App\Models\Novedad;
use App\Models\Emergency;
use App\Models\Staff;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Obtener estadísticas generales del sistema
     */
    public function index(Request $request)
    {
        try {
            // Obtener mes y año de los parámetros (por defecto mes y año actual)
            $mes = $request->get('mes', now()->month);
            $anio = $request->get('anio', now()->year);

            $stats = [
                // Estadísticas básicas
                'usuarios' => $this->getUserStats(),
                'incidencias' => $this->getIncidentStats(),
                'cursos' => $this->getCourseStats(),
                'clases' => $this->getClaseStats(),
                'reportes_diarios' => $this->getDailyReportStats(),
                'novedades' => $this->getNovedadStats(),
                'emergencias' => $this->getEmergencyStats(),
                'staff' => $this->getStaffStats(),
                
                // Métricas KPI
                'kpis' => $this->getKPIStats($mes, $anio),
                
                // Accesos y páginas vistas
                'accesos' => $this->getAccessStats($mes, $anio),
            ];

            return response()->json([
                'status' => 'success',
                'data' => $stats,
                'message' => 'Estadísticas obtenidas exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Estadísticas de usuarios
     */
    private function getUserStats()
    {
        return [
            'total' => User::count(),
            'por_rol' => User::select('rol', DB::raw('count(*) as count'))
                ->groupBy('rol')
                ->get()
                ->pluck('count', 'rol'),
            'este_mes' => User::whereMonth('created_at', now()->month)->count(),
            'esta_semana' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'recientes' => User::latest()->limit(5)->get(['id', 'name', 'email', 'rol', 'created_at']),
        ];
    }

    /**
     * Estadísticas de incidencias
     */
    private function getIncidentStats()
    {
        return [
            'total' => Incident::count(),
            'por_estado' => Incident::select('estado', DB::raw('count(*) as count'))
                ->groupBy('estado')
                ->get()
                ->pluck('count', 'estado'),
            'este_mes' => Incident::whereMonth('created_at', now()->month)->count(),
            'pendientes' => Incident::whereNotIn('estado', ['resuelta', 'no_resuelta'])->count(),
            'por_sala' => Incident::with('room')
                ->select('room_id', DB::raw('count(*) as count'))
                ->groupBy('room_id')
                ->get()
                ->map(function($item) {
                    return [
                        'sala' => $item->room ? $item->room->name : 'Sin sala',
                        'count' => $item->count
                    ];
                }),
        ];
    }

    /**
     * Estadísticas de cursos
     */
    private function getCourseStats()
    {
        return [
            'total' => Course::count(),
            'por_magister' => Course::with('magister')
                ->select('magister_id', DB::raw('count(*) as count'))
                ->groupBy('magister_id')
                ->get()
                ->map(function($item) {
                    return [
                        'magister' => $item->magister ? $item->magister->nombre : 'Sin magíster',
                        'count' => $item->count
                    ];
                }),
            'por_anio_ingreso' => Course::with('period')
                ->select('period_id', DB::raw('count(*) as count'))
                ->groupBy('period_id')
                ->get()
                ->map(function($item) {
                    return [
                        'anio_ingreso' => $item->period ? $item->period->anio_ingreso : 'Sin período',
                        'count' => $item->count
                    ];
                }),
        ];
    }

    /**
     * Estadísticas de clases
     */
    private function getClaseStats()
    {
        return [
            'total' => Clase::count(),
            'por_modalidad' => Clase::with('sesiones')
                ->get()
                ->flatMap(function($clase) {
                    return $clase->sesiones->pluck('modalidad');
                })
                ->countBy()
                ->toArray(),
            'por_sala' => Clase::with('room')
                ->select('room_id', DB::raw('count(*) as count'))
                ->groupBy('room_id')
                ->get()
                ->map(function($item) {
                    return [
                        'sala' => $item->room ? $item->room->name : 'Sin sala',
                        'count' => $item->count
                    ];
                }),
        ];
    }

    /**
     * Estadísticas de reportes diarios
     */
    private function getDailyReportStats()
    {
        try {
            return [
                'total' => DailyReport::count(),
                'este_mes' => DailyReport::whereMonth('created_at', now()->month)->count(),
                'esta_semana' => DailyReport::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'este_mes' => 0,
                'esta_semana' => 0,
            ];
        }
    }

    /**
     * Estadísticas de novedades
     */
    private function getNovedadStats()
    {
        return [
            'total' => Novedad::count(),
            'urgentes' => Novedad::where('es_urgente', true)
                ->where(function($q) {
                    $q->whereNull('fecha_expiracion')
                      ->orWhere('fecha_expiracion', '>', now());
                })->count(),
            'por_tipo' => Novedad::select('tipo_novedad', DB::raw('count(*) as count'))
                ->groupBy('tipo_novedad')
                ->get()
                ->pluck('count', 'tipo_novedad'),
            'publicas' => Novedad::where('visible_publico', true)->count(),
        ];
    }

    /**
     * Estadísticas de emergencias
     */
    private function getEmergencyStats()
    {
        return [
            'total' => Emergency::count(),
            'activas' => Emergency::where('active', true)
                ->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })->count(),
        ];
    }

    /**
     * Estadísticas de staff
     */
    private function getStaffStats()
    {
        return [
            'total' => Staff::count(),
            'por_cargo' => Staff::select('cargo', DB::raw('count(*) as count'))
                ->groupBy('cargo')
                ->get()
                ->pluck('count', 'cargo'),
        ];
    }

    /**
     * KPI: Métricas clave de rendimiento
     */
    private function getKPIStats($mes, $anio)
    {
        // 1. Tiempo promedio de respuesta a incidencias (en horas)
        $tiempoPromedio = Incident::whereIn('estado', ['resuelta', 'no_resuelta'])
            ->whereNotNull('updated_at')
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->get()
            ->map(function($incident) {
                return $incident->created_at->diffInHours($incident->updated_at);
            })
            ->average();

        // 2. Porcentaje de utilización del calendario
        $primerDia = Carbon::create($anio, $mes, 1);
        $ultimoDia = $primerDia->copy()->endOfMonth();
        
        $totalDiasHabiles = 0;
        $currentDay = $primerDia->copy();
        while ($currentDay <= $ultimoDia) {
            if ($currentDay->isWeekday()) {
                $totalDiasHabiles++;
            }
            $currentDay->addDay();
        }

        $diasConClases = Clase::with('sesiones')
            ->get()
            ->flatMap(function($clase) {
                return $clase->sesiones;
            })
            ->whereBetween('fecha', [$primerDia, $ultimoDia])
            ->pluck('fecha')
            ->unique()
            ->count();

        $porcentajeUtilizacion = $totalDiasHabiles > 0 
            ? round(($diasConClases / $totalDiasHabiles) * 100, 1) 
            : 0;

        return [
            'tiempo_promedio_respuesta' => round($tiempoPromedio ?? 0, 1),
            'porcentaje_utilizacion_calendario' => $porcentajeUtilizacion,
            'dias_con_clases' => $diasConClases,
            'total_dias_habiles' => $totalDiasHabiles,
        ];
    }

    /**
     * Estadísticas de accesos y páginas vistas
     */
    private function getAccessStats($mes, $anio)
    {
        // Verificar si existe la tabla page_views
        if (!DB::getSchemaBuilder()->hasTable('page_views')) {
            return [
                'accesos_mensuales' => 0,
                'sesiones_unicas' => 0,
                'calendario_publico' => 0,
                'calendario_autenticado' => 0,
                'registrados' => 0,
                'anonimos' => 0,
                'top_paginas' => [],
                'accesos_por_mes' => array_fill(1, 12, 0),
            ];
        }

        $accesosMensuales = PageView::whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->count();

        $sesionesUnicas = PageView::whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->distinct('session_id')
            ->count('session_id');

        $calendarioPublico = PageView::where('page_type', 'calendario_publico')
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->count();

        $calendarioAutenticado = PageView::where('page_type', 'calendario')
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->count();

        $accesosRegistrados = PageView::whereNotNull('user_id')
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->count();

        $accesosAnonimos = PageView::whereNull('user_id')
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->count();

        $topPaginas = PageView::select('page_type', DB::raw('count(*) as total'))
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $anio)
            ->groupBy('page_type')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'pagina' => $item->page_type,
                    'visitas' => $item->total,
                ];
            });

        // Accesos por cada mes del año
        $accesosPorMes = [];
        for ($i = 1; $i <= 12; $i++) {
            $accesosPorMes[$i] = PageView::whereMonth('created_at', $i)
                ->whereYear('created_at', $anio)
                ->count();
        }

        return [
            'accesos_mensuales' => $accesosMensuales,
            'sesiones_unicas' => $sesionesUnicas,
            'calendario_publico' => $calendarioPublico,
            'calendario_autenticado' => $calendarioAutenticado,
            'registrados' => $accesosRegistrados,
            'anonimos' => $accesosAnonimos,
            'top_paginas' => $topPaginas,
            'accesos_por_mes' => $accesosPorMes,
        ];
    }

    /**
     * Obtener estadísticas por período específico
     */
    public function periodStats(Request $request)
    {
        try {
            $anioIngreso = $request->get('anio_ingreso');
            $anio = $request->get('anio');
            $trimestre = $request->get('trimestre');

            if (!$anioIngreso || !$anio || !$trimestre) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Se requieren anio_ingreso, anio y trimestre'
                ], 400);
            }

            $period = \App\Models\Period::where('anio_ingreso', $anioIngreso)
                ->where('anio', $anio)
                ->where('numero', $trimestre)
                ->first();

            if (!$period) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Período no encontrado'
                ], 404);
            }

            $stats = [
                'periodo' => $period,
                'cursos' => Course::where('period_id', $period->id)->count(),
                'clases' => Clase::where('period_id', $period->id)->count(),
                'incidencias' => Incident::whereBetween('created_at', [$period->fecha_inicio, $period->fecha_fin])->count(),
            ];

            return response()->json([
                'status' => 'success',
                'data' => $stats,
                'message' => 'Estadísticas del período obtenidas exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener estadísticas del período: ' . $e->getMessage(),
            ], 500);
        }
    }
}
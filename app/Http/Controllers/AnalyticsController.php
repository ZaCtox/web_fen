<?php

namespace App\Http\Controllers;

use App\Models\PageView;
use App\Models\Period;
use App\Models\Event;
use App\Models\Incident;
use App\Models\ClaseSesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Muestra el dashboard de estadísticas generales
     */
    public function index(Request $request)
    {
        // Filtros simples: mes y año
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);
        
        // Obtener períodos del año seleccionado
        $periodos = Period::whereYear('fecha_inicio', $anio)
            ->orderBy('fecha_inicio')
            ->orderBy('numero')
            ->get();

        // ========================================
        // 1. TIEMPO PROMEDIO DE RESPUESTA A INCIDENCIAS
        // ========================================
        $incidenciasResueltas = Incident::where('estado', 'resuelta')
            ->whereNotNull('resuelta_en')
            ->when($anio, fn($q) => $q->whereYear('created_at', $anio))
            ->when($mes, fn($q) => $q->whereMonth('created_at', $mes))
            ->get();

        $tiempoPromedioIncidencias = $incidenciasResueltas->avg(function($inc) {
            return $inc->created_at->diffInHours($inc->resuelta_en);
        });
        $tiempoPromedioIncidencias = $tiempoPromedioIncidencias ? round($tiempoPromedioIncidencias, 1) : 0;

        // ========================================
        // 2. PORCENTAJE DE UTILIZACIÓN DEL CALENDARIO ACADÉMICO
        // ========================================
        
        // Calcular total de días hábiles en los períodos académicos del año seleccionado
        $totalDiasHabiles = 0;
        $periodosFiltrados = $periodos->when($anio, function($collection) use ($anio) {
            return $collection->filter(function($periodo) use ($anio) {
                return Carbon::parse($periodo->fecha_inicio)->year == $anio;
            });
        });

        foreach ($periodosFiltrados as $periodo) {
            $inicio = Carbon::parse($periodo->fecha_inicio);
            $fin = Carbon::parse($periodo->fecha_fin);
            
            // Contar solo días laborables (lunes a viernes)
            $diasHabiles = 0;
            $current = $inicio->copy();
            while ($current->lte($fin)) {
                if ($current->isWeekday()) {
                    $diasHabiles++;
                }
                $current->addDay();
            }
            $totalDiasHabiles += $diasHabiles;
        }

        // Contar días con al menos una sesión de clase programada
        $query = ClaseSesion::query();
        
        if ($periodosFiltrados->isNotEmpty()) {
            $query->where(function($q) use ($periodosFiltrados) {
                foreach ($periodosFiltrados as $periodo) {
                    $q->orWhereBetween('fecha', [
                        Carbon::parse($periodo->fecha_inicio),
                        Carbon::parse($periodo->fecha_fin)
                    ]);
                }
            });
        }
        
        $diasConClases = $query->select(DB::raw('DATE(fecha) as fecha_unica'))
            ->distinct()
            ->count();

        $porcentajeUtilizacionCalendario = $totalDiasHabiles > 0 
            ? round(($diasConClases / $totalDiasHabiles) * 100, 1) 
            : 0;

        // ========================================
        // 3. NÚMERO DE ACCESOS A LA PLATAFORMA
        // ========================================
        
        // Accesos totales del mes
        $accesosMensuales = PageView::whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->count();

        // Sesiones únicas del mes
        $sesionesUnicasMensuales = PageView::whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->distinct('session_id')
            ->count('session_id');

        // Accesos por tipo de página (mes actual)
        $accesosPorTipo = PageView::whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->select('page_type', DB::raw('count(*) as total'))
            ->groupBy('page_type')
            ->orderBy('total', 'desc')
            ->get();

        // Calendario público vs autenticado
        $accesosCalendarioPublico = PageView::where('page_type', 'calendario_publico')
            ->whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->count();

        $accesosCalendarioAutenticado = PageView::where('page_type', 'calendario_autenticado')
            ->whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->count();

        // Accesos diarios del mes (para gráfico)
        $accesosDiarios = PageView::whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->select(DB::raw('DATE(visited_at) as fecha'), DB::raw('count(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Accesos mensuales del año (para gráfico anual)
        $accesosMensualesAnio = PageView::whereYear('visited_at', $anio)
            ->select(DB::raw('MONTH(visited_at) as mes'), DB::raw('count(*) as total'))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->pluck('total', 'mes');

        // Completar meses faltantes con 0
        $mesesCompletos = collect(range(1, 12))->mapWithKeys(function($m) use ($accesosMensualesAnio) {
            return [$m => $accesosMensualesAnio->get($m, 0)];
        });

        // Top 5 páginas más visitadas
        $paginasMasVisitadas = PageView::whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->select('page_type', DB::raw('count(*) as total'))
            ->groupBy('page_type')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Usuarios registrados vs anónimos
        $accesosRegistrados = PageView::whereNotNull('user_id')
            ->whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->count();

        $accesosAnonimos = PageView::whereNull('user_id')
            ->whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->count();

        return view('analytics.index', compact(
            'tiempoPromedioIncidencias',
            'porcentajeUtilizacionCalendario',
            'accesosMensuales',
            'sesionesUnicasMensuales',
            'accesosPorTipo',
            'accesosCalendarioPublico',
            'accesosCalendarioAutenticado',
            'accesosDiarios',
            'mesesCompletos',
            'paginasMasVisitadas',
            'accesosRegistrados',
            'accesosAnonimos',
            'mes',
            'anio',
            'periodos',
            'totalDiasHabiles',
            'diasConClases'
        ));
    }

    /**
     * API endpoint para obtener estadísticas en JSON
     */
    public function api(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);

        return response()->json([
            'tiempo_promedio_incidencias' => $this->getTiempoPromedioIncidencias($mes, $anio),
            'porcentaje_utilizacion_calendario' => $this->getPorcentajeUtilizacionCalendario($anio),
            'accesos_mensuales' => $this->getAccesosMensuales($mes, $anio),
            'accesos_por_tipo' => $this->getAccesosPorTipo($mes, $anio),
        ]);
    }

    // Métodos auxiliares para la API
    private function getTiempoPromedioIncidencias($mes, $anio)
    {
        $incidenciasResueltas = Incident::where('estado', 'resuelta')
            ->whereNotNull('resuelta_en')
            ->whereYear('created_at', $anio)
            ->whereMonth('created_at', $mes)
            ->get();

        $tiempo = $incidenciasResueltas->avg(function($inc) {
            return $inc->created_at->diffInHours($inc->resuelta_en);
        });

        return $tiempo ? round($tiempo, 1) : 0;
    }

    private function getPorcentajeUtilizacionCalendario($anio)
    {
        // Implementación simplificada
        $periodos = Period::whereYear('fecha_inicio', $anio)->get();
        $totalDiasHabiles = 0;

        foreach ($periodos as $periodo) {
            $inicio = Carbon::parse($periodo->fecha_inicio);
            $fin = Carbon::parse($periodo->fecha_fin);
            
            // Contar solo días laborables (lunes a viernes)
            $diasHabiles = 0;
            $current = $inicio->copy();
            while ($current->lte($fin)) {
                if ($current->isWeekday()) {
                    $diasHabiles++;
                }
                $current->addDay();
            }
            $totalDiasHabiles += $diasHabiles;
        }

        // Contar días únicos con sesiones de clase
        $diasConClases = ClaseSesion::whereHas('clase', function($q) use ($anio) {
                $q->whereHas('period', function($qq) use ($anio) {
                    $qq->whereYear('fecha_inicio', $anio);
                });
            })
            ->select(DB::raw('DATE(fecha) as fecha_unica'))
            ->distinct()
            ->count();

        return $totalDiasHabiles > 0 ? round(($diasConClases / $totalDiasHabiles) * 100, 1) : 0;
    }

    private function getAccesosMensuales($mes, $anio)
    {
        return PageView::whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->count();
    }

    private function getAccesosPorTipo($mes, $anio)
    {
        return PageView::whereMonth('visited_at', $mes)
            ->whereYear('visited_at', $anio)
            ->select('page_type', DB::raw('count(*) as total'))
            ->groupBy('page_type')
            ->orderBy('total', 'desc')
            ->get();
    }
}


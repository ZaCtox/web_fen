<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\Room;
use App\Models\Course;
use App\Models\Incident;
use App\Models\Emergency;
use App\Models\DailyReport;
use App\Models\Magister;
use App\Models\Period;
use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        try {
            // Estadísticas generales
            $stats = [
                'users' => [
                    'total' => User::count(),
                    'by_role' => User::select('rol', DB::raw('count(*) as count'))
                        ->groupBy('rol')
                        ->get()
                        ->pluck('count', 'rol')
                ],
                'staff' => Staff::count(),
                'rooms' => Room::count(),
                'magisters' => Magister::count(),
                'courses' => Course::count(),
                'clases' => Clase::count(),
                'periods' => Period::count(),
                'incidents' => [
                    'total' => Incident::count(),
                    'by_status' => Incident::select('estado', DB::raw('count(*) as count'))
                        ->groupBy('estado')
                        ->get()
                        ->pluck('count', 'estado')
                ],
                'emergencies' => [
                    'total' => Emergency::count(),
                    'active' => Emergency::where('active', true)->count()
                ],
                'daily_reports' => [
                    'total' => DailyReport::count(),
                    'this_month' => DailyReport::whereMonth('created_at', now()->month)->count(),
                    'this_week' => DailyReport::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()
                ]
            ];

            // Estadísticas de severidad de reportes diarios
            $severityStats = [];
            for ($i = 1; $i <= 10; $i++) {
                $count = DB::table('report_entries')
                    ->where('escala', $i)
                    ->count();
                $severityStats[$i] = $count;
            }

            // Estadísticas por programa
            $programStats = DB::table('report_entries')
                ->select('programa', DB::raw('count(*) as count'))
                ->whereNotNull('programa')
                ->groupBy('programa')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
                ->pluck('count', 'programa');

            // Estadísticas por área
            $areaStats = DB::table('report_entries')
                ->select('area', DB::raw('count(*) as count'))
                ->whereNotNull('area')
                ->groupBy('area')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
                ->pluck('count', 'area');

            // Actividad reciente
            $recentActivity = [
                'recent_users' => User::latest()->limit(5)->get(['id', 'name', 'email', 'rol', 'created_at']),
                'recent_incidents' => Incident::with('room')->latest()->limit(5)->get(['id', 'titulo', 'estado', 'room_id', 'created_at']),
                'recent_daily_reports' => DailyReport::with('user')->latest()->limit(5)->get(['id', 'title', 'user_id', 'created_at'])
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => $stats,
                    'severity_scale' => $severityStats,
                    'program_statistics' => $programStats,
                    'area_statistics' => $areaStats,
                    'recent_activity' => $recentActivity
                ],
                'message' => 'Dashboard de administrador cargado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el dashboard: ' . $e->getMessage()
            ], 500);
        }
    }
}


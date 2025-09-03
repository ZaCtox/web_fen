<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Room;
use App\Models\RoomAssignment;
use App\Models\AccessLog;
use App\Models\UserSatisfaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Dashboard",
 *     description="API Endpoints para el dashboard administrativo"
 * )
 */
class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/dashboard/stats",
     *     summary="Obtener estadísticas del dashboard",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Estadísticas obtenidas exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getStats(): JsonResponse
    {
        // Estadísticas de incidencias
        $incidentStats = [
            'total' => Incident::count(),
            'pendientes' => Incident::where('estado', 'pendiente')->count(),
            'en_progreso' => Incident::where('estado', 'en_progreso')->count(),
            'resueltas' => Incident::where('estado', 'resuelta')->count(),
            'por_mes' => Incident::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
        ];

        // Estadísticas de uso de salas
        $roomStats = [
            'total_salas' => Room::count(),
            'salas_asignadas' => RoomAssignment::where('is_active', true)->distinct('room_id')->count(),
            'uso_por_sala' => Room::withCount(['roomAssignments' => function($query) {
                $query->where('is_active', true);
            }])->orderBy('room_assignments_count', 'desc')->take(10)->get()
        ];

        // Estadísticas de accesos
        $accessStats = [
            'total_accesos' => AccessLog::count(),
            'accesos_hoy' => AccessLog::whereDate('created_at', today())->count(),
            'accesos_esta_semana' => AccessLog::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'accesos_por_endpoint' => AccessLog::selectRaw('endpoint, COUNT(*) as count')
                ->whereNotNull('endpoint')
                ->groupBy('endpoint')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get()
        ];

        // Estadísticas de satisfacción
        $satisfactionStats = [
            'promedio_general' => UserSatisfaction::avg('rating'),
            'total_evaluaciones' => UserSatisfaction::count(),
            'por_categoria' => UserSatisfaction::selectRaw('category, AVG(rating) as avg_rating, COUNT(*) as count')
                ->groupBy('category')
                ->get(),
            'distribucion_ratings' => UserSatisfaction::selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating')
                ->get()
        ];

        // Estadísticas de eventos académicos
        $eventStats = [
            'total_eventos' => \App\Models\AcademicEvent::count(),
            'eventos_hoy' => \App\Models\AcademicEvent::whereDate('start_date', today())->count(),
            'eventos_esta_semana' => \App\Models\AcademicEvent::whereBetween('start_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'por_tipo' => \App\Models\AcademicEvent::selectRaw('event_type, COUNT(*) as count')
                ->groupBy('event_type')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'incidentes' => $incidentStats,
                'salas' => $roomStats,
                'accesos' => $accessStats,
                'satisfaccion' => $satisfactionStats,
                'eventos' => $eventStats,
                'generado_en' => now()->format('d/m/Y H:i:s')
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/incidents-trend",
     *     summary="Obtener tendencia de incidencias",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="days",
     *         in="query",
     *         @OA\Schema(type="integer", default=30)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tendencia obtenida exitosamente"
     *     )
     * )
     */
    public function getIncidentsTrend(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        
        $trend = Incident::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trend
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/room-usage",
     *     summary="Obtener uso de salas",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="academic_year",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="trimester",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Uso de salas obtenido exitosamente"
     *     )
     * )
     */
    public function getRoomUsage(Request $request): JsonResponse
    {
        $query = RoomAssignment::with(['room', 'subject.program']);

        if ($request->has('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->has('trimester')) {
            $query->where('trimester', $request->trimester);
        }

        $usage = $query->where('is_active', true)
            ->get()
            ->groupBy('room_id')
            ->map(function ($assignments, $roomId) {
                $room = $assignments->first()->room;
                return [
                    'room' => $room,
                    'assignments_count' => $assignments->count(),
                    'assignments' => $assignments->load('subject.program')
                ];
            })
            ->sortByDesc('assignments_count')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $usage
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/user-activity",
     *     summary="Obtener actividad de usuarios",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="days",
     *         in="query",
     *         @OA\Schema(type="integer", default=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actividad de usuarios obtenida exitosamente"
     *     )
     * )
     */
    public function getUserActivity(Request $request): JsonResponse
    {
        $days = $request->get('days', 7);
        
        $activity = AccessLog::with('user')
            ->selectRaw('user_id, COUNT(*) as access_count, MAX(created_at) as last_access')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('user_id')
            ->orderBy('access_count', 'desc')
            ->take(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $activity
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/dashboard/satisfaction-summary",
     *     summary="Obtener resumen de satisfacción",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Resumen de satisfacción obtenido exitosamente"
     *     )
     * )
     */
    public function getSatisfactionSummary(): JsonResponse
    {
        $summary = [
            'overall_average' => UserSatisfaction::avg('rating'),
            'total_ratings' => UserSatisfaction::count(),
            'category_breakdown' => UserSatisfaction::selectRaw('category, AVG(rating) as avg_rating, COUNT(*) as count')
                ->groupBy('category')
                ->get(),
            'recent_ratings' => UserSatisfaction::with('user')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}

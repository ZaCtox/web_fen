<?php

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\Period;

// Rutas públicas existentes
Route::get('/trimestre-siguiente', function (Request $request) {
    $fecha = Carbon::parse($request->query('fecha'));

    $siguiente = Period::whereDate('fecha_inicio', '>', $fecha)
        ->orderBy('fecha_inicio')
        ->first();

    return response()->json([
        'fecha_inicio' => $siguiente?->fecha_inicio?->toDateString(),
        'anio' => $siguiente?->anio,
        'numero' => $siguiente?->numero,
        'error' => $siguiente ? null : 'No se encontró trimestre siguiente.'
    ]);
});

Route::get('/trimestre-anterior', function (Request $request) {
    $fecha = Carbon::parse($request->query('fecha'));

    $anterior = Period::whereDate('fecha_fin', '<', $fecha)
        ->orderByDesc('fecha_fin')
        ->first();

    return response()->json([
        'fecha_inicio' => $anterior?->fecha_inicio?->toDateString(),
        'anio' => $anterior?->anio,
        'numero' => $anterior?->numero,
        'error' => $anterior ? null : 'No se encontró trimestre anterior.'
    ]);
});

Route::get('/periodo-por-fecha', function (Request $request) {
    $fecha = Carbon::parse($request->query('fecha'));

    $periodo = Period::where('fecha_inicio', '<=', $fecha)
        ->where('fecha_fin', '>=', $fecha)
        ->first();

    return response()->json(['periodo' => $periodo]);
});

Route::get('/trimestres-todos', function () {
    return \App\Models\Period::orderBy('fecha_inicio')->get(['fecha_inicio']);
});

// Rutas protegidas con autenticación
Route::middleware(['auth:sanctum', 'log.api.access'])->group(function () {
    
    // Rutas para programas de magíster
    Route::apiResource('programs', \App\Http\Controllers\Api\ProgramController::class)
        ->middleware('role:administrador');
    
    // Rutas para asignaturas
    Route::apiResource('subjects', \App\Http\Controllers\Api\SubjectController::class)
        ->middleware('role:administrador');
    
    // Rutas para asignaciones de salas
    Route::apiResource('room-assignments', \App\Http\Controllers\Api\RoomAssignmentController::class)
        ->middleware('role:administrador');
    
    // Rutas para eventos académicos
    Route::apiResource('academic-events', \App\Http\Controllers\Api\AcademicEventController::class)
        ->middleware('role:administrador,docente');
    
    // Rutas para incidencias
    Route::apiResource('incidents', \App\Http\Controllers\Api\IncidentController::class)
        ->middleware('role:administrador,docente,estudiante');
    
    // Rutas adicionales para incidencias
    Route::post('incidents/{incident}/images', [\App\Http\Controllers\Api\IncidentController::class, 'addImages'])
        ->middleware('role:administrador,docente,estudiante');
    
    Route::get('incidents/report/pdf', [\App\Http\Controllers\Api\IncidentController::class, 'generatePdfReport'])
        ->middleware('role:administrador');
    
    // Rutas para el dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('stats', [\App\Http\Controllers\Api\DashboardController::class, 'getStats'])
            ->middleware('role:administrador');
        
        Route::get('incidents-trend', [\App\Http\Controllers\Api\DashboardController::class, 'getIncidentsTrend'])
            ->middleware('role:administrador');
        
        Route::get('room-usage', [\App\Http\Controllers\Api\DashboardController::class, 'getRoomUsage'])
            ->middleware('role:administrador');
        
        Route::get('user-activity', [\App\Http\Controllers\Api\DashboardController::class, 'getUserActivity'])
            ->middleware('role:administrador');
        
        Route::get('satisfaction-summary', [\App\Http\Controllers\Api\DashboardController::class, 'getSatisfactionSummary'])
            ->middleware('role:administrador');
    });
    
    // Rutas para satisfacción de usuarios
    Route::post('satisfaction', function (Request $request) {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'category' => 'required|in:general,salas,incidencias,eventos',
        ]);
        
        $satisfaction = \App\Models\UserSatisfaction::create([
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'category' => $validated['category'],
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Evaluación registrada exitosamente',
            'data' => $satisfaction
        ]);
    })->middleware('role:administrador,docente,estudiante');
    
    // Ruta para obtener perfil del usuario autenticado
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load('roles')
        ]);
    });
});

// Ruta de prueba para verificar que la API funciona
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now()->toISOString(),
        'message' => 'API funcionando correctamente'
    ]);
});
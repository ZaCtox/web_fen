<?php

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\Period;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ClaseController;
use App\Http\Controllers\Api\MagisterController;
use App\Http\Controllers\Api\EmergencyController;
use App\Http\Controllers\Api\PeriodController;

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




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas protegidas por rol
    Route::middleware('role.api:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index']);
    });

    Route::middleware('role.api:user')->group(function () {
        Route::get('/user/profile', [UserController::class, 'profile']);
    });
});


Route::apiResource('staff', StaffController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('periods', PeriodController::class);
Route::apiResource('magisters', MagisterController::class);
Route::get('incidents/estadisticas', [IncidentController::class, 'estadisticas']);
Route::apiResource('incidents', IncidentController::class);
Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{event}', [EventController::class, 'update']);
Route::delete('/events/{event}', [EventController::class, 'destroy']);
Route::get('/emergencies', [EmergencyController::class, 'index']);
Route::post('/emergencies', [EmergencyController::class, 'store']);
Route::put('/emergencies/{id}', [EmergencyController::class, 'update']);
Route::delete('/emergencies/{id}', [EmergencyController::class, 'destroy']);
Route::patch('/emergencies/{id}/deactivate', [EmergencyController::class, 'deactivate']);
// pública, para que la app consulte la emergencia activa
Route::get('/emergencies/active', [EmergencyController::class, 'active']);
Route::get('/courses', [CourseController::class, 'index']);
Route::post('/courses', [CourseController::class, 'store']);
Route::get('/courses/{course}', [CourseController::class, 'show']);
Route::put('/courses/{course}', [CourseController::class, 'update']);
Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
Route::get('/clases', [ClaseController::class, 'index']);
Route::post('/clases', [ClaseController::class, 'store']);
Route::get('/clases/{clase}', [ClaseController::class, 'show']);
Route::put('/clases/{clase}', [ClaseController::class, 'update']);
Route::delete('/clases/{clase}', [ClaseController::class, 'destroy']);
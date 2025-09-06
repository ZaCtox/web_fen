<?php

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\Period;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\StaffController;
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
Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/staff', [StaffController::class, 'index']);
Route::get('/clases', [ClaseController::class, 'index']);
Route::get('/magisters', [MagisterController::class, 'index']);
Route::get('/magisters/{id}', [MagisterController::class, 'show']);
Route::get('/emergencies', [EmergencyController::class, 'index']);
Route::get('/emergencies/active', [EmergencyController::class, 'active']);

Route::get('periods', [PeriodController::class, 'index']);
Route::get('periods/{id}', [PeriodController::class, 'show']);
Route::get('periodo-por-fecha', [PeriodController::class, 'periodoPorFecha']);



<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\IncidenciaController;
use App\Http\Controllers\Api\MagisterController;
use App\Http\Controllers\Api\CourseController;

Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/eventos', [EventController::class, 'index']);
Route::get('/ping', function () {
    return response()->json(['message' => 'API activa']);
});
Route::get('/incidencias', [IncidenciaController::class, 'index']);
Route::get('/magisteres', [MagisterController::class, 'index']);
Route::get('/cursos', [CourseController::class, 'index']);
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicSite\PublicDashboardController;
use App\Http\Controllers\PublicSite\PublicStaffController;
use App\Http\Controllers\PublicSite\PublicRoomController;
use App\Http\Controllers\PublicSite\PublicCalendarioController;
use App\Http\Controllers\PublicSite\PublicCourseController;

// Página principal pública (antes '/')
Route::middleware('redirect.authenticated')->group(function () {
Route::get('/', [PublicDashboardController::class, 'index'])->name('public.dashboard.index');

// Calendario público
Route::get('/calendario-academico', [PublicCalendarioController::class, 'index'])->name('public.calendario.index');

// Staff
Route::get('/staff-fen', [PublicStaffController::class, 'index'])->name('public.staff.index');

// Salas
Route::get('/salas-fen', [PublicRoomController::class, 'index'])->name('public.rooms.index');

// Cursos por Magíster
Route::get('/cursos-fen', [PublicCourseController::class, 'index'])->name('public.courses.index');
});
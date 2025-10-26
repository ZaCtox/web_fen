<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicSite\GuestEventController;
use App\Http\Controllers\PublicSite\PublicCalendarioController;
use App\Http\Controllers\PublicSite\PublicClaseController;
use App\Http\Controllers\PublicSite\PublicCourseController;
use App\Http\Controllers\PublicSite\PublicDashboardController;
use App\Http\Controllers\PublicSite\PublicRoomController;
use App\Http\Controllers\PublicSite\PublicStaffController;
use App\Http\Controllers\PublicSite\PublicInformeController;
use Illuminate\Support\Facades\Route;

// Página principal pública (antes '/')
// Página pública
Route::get('/', [PublicDashboardController::class, 'index'])
    ->name('public.dashboard.index');

// Dashboard para usuarios autenticados
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Calendario público
Route::get('/Calendario-Academico', [PublicCalendarioController::class, 'index'])->name('public.calendario.index');
// Eventos en modo solo lectura
Route::get('/guest-events', [GuestEventController::class, 'index'])->name('guest.events.index');

// Staff
Route::get('/Equipo-FEN', [PublicStaffController::class, 'index'])->name('public.Equipo-FEN.index');

// Salas
Route::get('/Salas-FEN', [PublicRoomController::class, 'index'])->name('public.rooms.index');

// Cursos por Magíster
Route::get('/Módulos-FEN', [PublicCourseController::class, 'index'])->name('public.courses.index');

Route::get('/public/clases/{clase}', [PublicClaseController::class, 'show'])
    ->name('public.clases.show');

Route::get('public/rooms/{room}', [PublicRoomController::class, 'show'])
    ->name('public.rooms.show');

// Archivos públicos
Route::get('/Archivos-FEN', [PublicInformeController::class, 'index'])
    ->name('public.informes.index');

Route::get('/Archivos-FEN/download/{id}', [PublicInformeController::class, 'download'])
    ->name('public.informes.download');

// Novedades públicas
Route::get('/Novedades-FEN', [PublicDashboardController::class, 'novedades'])
    ->name('public.novedades');
Route::get('/Novedades-FEN/{novedad}', [PublicDashboardController::class, 'novedadDetalle'])
    ->name('public.novedades.show');

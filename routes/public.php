<?php

<<<<<<< Updated upstream
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicSite\PublicDashboardController;
use App\Http\Controllers\PublicSite\PublicStaffController;
use App\Http\Controllers\PublicSite\PublicRoomController;
use App\Http\Controllers\PublicSite\PublicCalendarioController;
use App\Http\Controllers\PublicSite\PublicCourseController;
use App\Http\Controllers\PublicSite\GuestEventController;
use App\Http\Controllers\PublicSite\PublicClaseController;
use App\Http\Controllers\DashboardController;
=======
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicSite\GuestEventController;
use App\Http\Controllers\PublicSite\PublicCalendarioController;
use App\Http\Controllers\PublicSite\PublicClaseController;
use App\Http\Controllers\PublicSite\PublicCourseController;
use App\Http\Controllers\PublicSite\PublicDashboardController;
use App\Http\Controllers\PublicSite\PublicRoomController;
use App\Http\Controllers\PublicSite\PublicStaffController;
use Illuminate\Support\Facades\Route;
>>>>>>> Stashed changes

// Página principal pública (antes '/')
// Página pública
Route::get('/', [PublicDashboardController::class, 'index'])
    ->name('public.dashboard.index');

// Dashboard para usuarios autenticados
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
// Calendario público
Route::get('/Calendario-Academico', [PublicCalendarioController::class, 'index'])->name('public.calendario.index');
// Eventos en modo solo lectura
Route::get('/guest-events', [GuestEventController::class, 'index'])->name('guest.events.index');

// Staff
Route::get('/Equipo-FEN', [PublicStaffController::class, 'index'])->name('public.Equipo-FEN.index');

// Salas
Route::get('/Salas-FEN', [PublicRoomController::class, 'index'])->name('public.rooms.index');

// Cursos por Magíster
Route::get('/Cursos-FEN', [PublicCourseController::class, 'index'])->name('public.courses.index');

Route::get('/public/clases/{clase}', [PublicClaseController::class, 'show'])
    ->name('public.clases.show');
<<<<<<< Updated upstream
=======

Route::get('public/rooms/{room}', [PublicRoomController::class, 'show'])
    ->name('public.rooms.show');
>>>>>>> Stashed changes

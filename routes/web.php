<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\MagisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;
use App\Models\Incident;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\GuestDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\OnlineClassController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\GuestEventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PublicStaffController;
use App\Http\Controllers\PublicRoomController;



// Página de inicio pública
Route::get('/', function (Request $request) {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return app(GuestDashboardController::class)->index($request);
})->name('guest.dashboard');

// Solo Lectura
Route::get('/guest-events', [GuestEventController::class, 'index'])->name('guest.events.index');
/* Route::get('/salas', [PublicRoomController::class, 'index'])->name('public.room.index');
Route::get('/staff-fen', [PublicStaffController::class, 'index'])->name('public.staff.index'); */




Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // Acceso exclusivo para ADMINISTRATIVOS
    Route::middleware('role:administrativo')->group(function () {
        // Perfil de usuario
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Incidencias
        Route::get('/incidencias/estadisticas', [IncidentController::class, 'estadisticas'])->name('incidencias.estadisticas');
        Route::get('/incidencias/exportar-pdf', [IncidentController::class, 'exportarPDF'])->name('incidencias.exportar.pdf');
        Route::resource('incidencias', IncidentController::class);

        // Eventos
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

        // Periodos
        Route::resource('periods', PeriodController::class);

        // Cursos y Magísteres
        Route::resource('courses', CourseController::class);
        Route::delete('/courses/programa/{programa}', [CourseController::class, 'destroyPrograma'])->name('courses.destroy-programa');
        Route::resource('magisters', MagisterController::class);

        // Staff
        Route::resource('staff', StaffController::class);
    });

    // Acceso para ADMINISTRATIVOS y DOCENTES
    Route::middleware('role:administrativo,docente')->group(function () {
        // Salas
        Route::resource('rooms', RoomController::class);
        Route::get('/rooms/{room}/asignar-uso', [RoomController::class, 'asignarUso'])->name('rooms.asignar');
        Route::post('/rooms/{room}/asignar-uso', [RoomController::class, 'guardarUso'])->name('rooms.guardar-uso');

        // Clases
        Route::resource('clases', ClaseController::class);
        Route::get('/clases/exportar', [ClaseController::class, 'exportar'])->name('clases.exportar');
        Route::get('/clases-exportar', [ClaseController::class, 'exportar'])->name('clases.exportar'); // redundante

        // Vista de calendario editable
        Route::view('/calendario', 'calendario.index')->name('calendario');
    });
});

require __DIR__ . '/auth.php';

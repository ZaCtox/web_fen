<?php

use App\Http\Controllers\ClaseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\MagisterController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 🏠 Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:decano,director_administrativo,docente,administrativo'])
    ->name('dashboard');

// 🟡 Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // 📚 Clases
    Route::get('/clases/exportar', [ClaseController::class, 'exportar'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
        ->name('clases.exportar');

    Route::resource('clases', ClaseController::class)
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado');

    Route::get('/salas/disponibilidad', [ClaseController::class, 'disponibilidad'])
        ->middleware('role:decano,asistente_programa,director_administrativo')
        ->name('salas.disponibilidad');

    Route::get('/salas/horarios', [ClaseController::class, 'horariosDisponibles'])
        ->middleware('role:decano,asistente_programa,director_administrativo')
        ->name('salas.horarios');

    // 📅 Calendario
    Route::get('/calendario', [EventController::class, 'calendario'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
        ->name('calendario');

    // 🎉 Eventos (se asume mismo alcance que calendario)
    Route::get('/events', [EventController::class, 'index'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
        ->name('events.index');
    Route::post('/events', [EventController::class, 'store'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
        ->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
        ->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
        ->name('events.destroy');

    // 🗂️ Incidencias
    Route::get('/incidencias/estadisticas', [IncidentController::class, 'estadisticas'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,tecnico,auxiliar,asistente_postgrado')
        ->name('incidencias.estadisticas');

    Route::get('/incidencias/exportar-pdf', [IncidentController::class, 'exportarPDF'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,tecnico,auxiliar,asistente_postgrado')
        ->name('incidencias.exportar.pdf');

    Route::resource('incidencias', IncidentController::class)
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,tecnico,auxiliar,asistente_postgrado');

    // 🏛️ Salas
    Route::resource('rooms', RoomController::class)
        ->middleware('role:decano,asistente_programa,director_administrativo');

    // 👤 Perfil (todos los logueados)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 👥 Usuarios
    Route::resource('usuarios', UserController::class)
        ->middleware('role:decano,director_administrativo');

    // 📆 Periodos
    Route::resource('periods', PeriodController::class)
        ->middleware('role:decano,director_administrativo');
    Route::post('/periods/actualizar-proximo-anio', [PeriodController::class, 'actualizarAlProximoAnio'])
        ->middleware('role:decano,director_administrativo')
        ->name('periods.actualizarProximoAnio');

    // 🎓 Cursos
    Route::resource('courses', CourseController::class)
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo');
    Route::delete('/courses/programa/{programa}', [CourseController::class, 'destroyPrograma'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo')
        ->name('courses.destroy-programa');

    // 📘 Magísteres
    Route::resource('magisters', MagisterController::class)
        ->except(['show'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo');

    // 👨‍🏫 Staff
    Route::resource('staff', StaffController::class)
        ->middleware('role:decano,director_administrativo');

    // 🚨 Emergencias
    Route::post('/emergency', [EmergencyController::class, 'store'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
        ->name('emergency.store');

    Route::resource('emergencies', EmergencyController::class)
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado');

    Route::patch('emergencies/{id}/deactivate', [EmergencyController::class, 'deactivate'])
        ->middleware('role:decano,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
        ->name('emergencies.deactivate');
});

require __DIR__.'/public.php';
require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CourseController,
    MagisterController,
    ProfileController,
    IncidentController,
    EventController,
    RoomController,
    PeriodController,
    ClaseController,
    DashboardController,
    StaffController,
    UserController,
    EmergencyController
};

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

    // 🎉 Eventos
    Route::get('/events', [EventController::class, 'index'])
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado')
        ->name('events.index');
    Route::post('/events', [EventController::class, 'store'])
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado')
        ->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado')
        ->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado')
        ->name('events.destroy');

    // 🗂️ Incidencias
    Route::get('/incidencias/estadisticas', [IncidentController::class, 'estadisticas'])
        ->middleware('role:administrador,director_programa,asistente_programa,tecnico,auxiliar,asistente_postgrado')
        ->name('incidencias.estadisticas');

    Route::get('/incidencias/exportar-pdf', [IncidentController::class, 'exportarPDF'])
        ->middleware('role:administrador,director_programa,asistente_programa,tecnico,auxiliar,asistente_postgrado')
        ->name('incidencias.exportar.pdf');

    Route::resource('incidencias', IncidentController::class)
        ->middleware('role:administrador,director_programa,asistente_programa,tecnico,auxiliar,asistente_postgrado');

    // 🏛️ Salas
    Route::resource('rooms', RoomController::class)
        ->middleware('role:administrador,asistente_programa');

    // 👤 Perfil (todos los logueados pueden acceder a su perfil)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 👥 Usuarios (solo administrador)
    Route::resource('usuarios', UserController::class)
        ->middleware('role:administrador');

    // 📆 Periodos
    Route::resource('periods', PeriodController::class)
        ->middleware('role:administrador');
    Route::post('/periods/actualizar-proximo-anio', [PeriodController::class, 'actualizarAlProximoAnio'])
        ->middleware('role:administrador')
        ->name('periods.actualizarProximoAnio');

    // 🎓 Cursos
    Route::resource('courses', CourseController::class)
        ->middleware('role:administrador,director_programa,asistente_programa');
    Route::delete('/courses/programa/{programa}', [CourseController::class, 'destroyPrograma'])
        ->middleware('role:administrador,director_programa,asistente_programa')
        ->name('courses.destroy-programa');

    // 📘 Magísteres
    Route::resource('magisters', MagisterController::class)
        ->except(['show'])
        ->middleware('role:administrador,director_programa,asistente_programa');

    // 👨‍🏫 Staff
    Route::resource('staff', StaffController::class)
        ->middleware('role:administrador');

    // 🚨 Emergencias
    Route::post('/emergency', [EmergencyController::class, 'store'])
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado')
        ->name('emergency.store');

    Route::resource('emergencies', EmergencyController::class)
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado');

    Route::patch('emergencies/{id}/deactivate', [EmergencyController::class, 'deactivate'])
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado')
        ->name('emergencies.deactivate');
});

require __DIR__ . '/public.php';
require __DIR__ . '/auth.php';

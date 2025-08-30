<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    CourseController,
    MagisterController,
    ProfileController,
    IncidentController,
    EventController,
    RoomController,
    PeriodController,
    GuestDashboardController,
    OnlineClassController,
    ClaseController,
    GuestEventController,
    DashboardController,
    StaffController,
    PublicStaffController,
    PublicRoomController,
    UserController,
    EmergencyController
};



// 🌐 API pública para calendario de invitados
Route::prefix('/api')->group(function () {
    Route::get('/periodo-por-fecha', [PeriodController::class, 'periodoPorFecha']);
    Route::get('/trimestre-siguiente', [PeriodController::class, 'trimestreSiguiente']);
    Route::get('/trimestre-anterior', [PeriodController::class, 'trimestreAnterior']);
});


// Dashboard principal (solo autenticados y verificados)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 🟡 Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // Clases
    Route::get('/clases/exportar', [ClaseController::class, 'exportar'])->name('clases.exportar');
    Route::resource('clases', ClaseController::class);
    Route::get('/salas/disponibilidad', [ClaseController::class, 'disponibilidad'])->name('salas.disponibilidad');
    Route::get('/salas/horarios', [ClaseController::class, 'horariosDisponibles'])->name('salas.horarios');

    // Calendario
    Route::get('/calendario', [EventController::class, 'calendario'])->name('calendario');


    // Eventos
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Incidencias
    Route::get('/incidencias/estadisticas', [IncidentController::class, 'estadisticas'])->name('incidencias.estadisticas');
    Route::get('/incidencias/exportar-pdf', [IncidentController::class, 'exportarPDF'])->name('incidencias.exportar.pdf');
    Route::resource('incidencias', IncidentController::class);

    // Salas
    Route::resource('rooms', RoomController::class);

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');

    // Periodos
    Route::resource('periods', PeriodController::class);
    Route::post('/periods/actualizar-proximo-anio', [PeriodController::class, 'actualizarAlProximoAnio'])->name('periods.actualizarProximoAnio');


    // Cursos y Magísteres
    Route::resource('courses', CourseController::class);
    Route::delete('/courses/programa/{programa}', [CourseController::class, 'destroyPrograma'])->name('courses.destroy-programa');
    Route::resource('magisters', MagisterController::class)->except(['show']);

    // Staff
    Route::resource('staff', StaffController::class);

    // Alerta
    Route::post('/emergency', [EmergencyController::class, 'store'])->name('emergency.store');
    Route::resource('emergencies', EmergencyController::class);
    Route::patch('emergencies/{id}/deactivate', [EmergencyController::class, 'deactivate'])->name('emergencies.deactivate');

});
require __DIR__ . '/public.php';

require __DIR__ . '/auth.php';

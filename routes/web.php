<?php

use App\Http\Controllers\ClaseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\MagisterController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BitacoraController;
use Illuminate\Support\Facades\Route;


// 🏠 Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:administrador,director_administrativo,docente,administrativo'])
    ->name('dashboard');

// 🧠 Demo de Principios HCI
Route::get('/hci-demo', function () {
    return view('examples.hci-demo');
})->middleware(['auth', 'role:administrador'])->name('hci.demo');

// 🎨 Demo de Microinteracciones HCI
Route::get('/microinteractions-demo', function () {
    return view('examples.microinteractions-demo');
})->middleware(['auth', 'role:administrador'])->name('microinteractions.demo');

// 🟡 Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // 📚 Clases
    Route::get('/clases/exportar', [ClaseController::class, 'exportar'])
        ->middleware('role:administrador,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
        ->name('clases.exportar');

    Route::resource('clases', ClaseController::class)
        ->middleware('role:administrador,director_programa,asistente_programa,director_administrativo,asistente_postgrado');

    Route::get('/salas/disponibilidad', [ClaseController::class, 'disponibilidad'])
        ->middleware('role:administrador,asistente_programa,director_administrativo')
        ->name('salas.disponibilidad');

    Route::get('/salas/horarios', [ClaseController::class, 'horariosDisponibles'])
        ->middleware('role:administrador,asistente_programa,director_administrativo')
        ->name('salas.horarios');

    // 📅 Calendario
    Route::get('/calendario', [EventController::class, 'calendario'])
        ->middleware('role:administrador,director_programa,asistente_programa,director_administrativo,asistente_postgrado')
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
        ->middleware('role:administrador,director_programa,asistente_programa,técnico,auxiliar,asistente_postgrado')
        ->name('incidencias.estadisticas');

    Route::get('/incidencias/exportar-pdf', [IncidentController::class, 'exportarPDF'])
        ->middleware('role:administrador,director_programa,asistente_programa,técnico,auxiliar,asistente_postgrado')
        ->name('incidencias.exportar.pdf');

    Route::resource('incidencias', IncidentController::class)
        ->middleware('role:administrador,director_programa,asistente_programa,técnico,auxiliar,asistente_postgrado');

    // 🏛️ Salas
    Route::resource('rooms', RoomController::class)
        ->middleware('role:administrador,asistente_programa');

    // 👤 Perfil (todos los logueados pueden acceder a su perfil)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // 🔔 Notificaciones (tabla personalizada notifications)
    Route::patch('/notifications/{notification}/read', function($notificationId) {
        \DB::table('notifications')
            ->where('id', $notificationId)
            ->where('user_id', auth()->id())
            ->update(['read' => 1, 'updated_at' => now()]);
        return response()->json(['success' => true]);
    })->name('notifications.mark-read');

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

    Route::resource('informes', InformeController::class)->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado');

    Route::get('informes/download/{id}', [InformeController::class, 'download'])->name('informes.download');

    Route::resource('bitacoras', BitacoraController::class)
        ->middleware('role:asistente_postgrado');

    Route::get('bitacoras/download/{bitacora}', [BitacoraController::class, 'download'])
        ->name('bitacoras.download')
        ->middleware('role:asistente_postgrado');

});

require __DIR__.'/public.php';
require __DIR__.'/auth.php';

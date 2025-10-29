<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\MagisterController;
use App\Http\Controllers\NovedadController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// 🏠 Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:administrador,director_administrativo,decano,docente,administrativo,asistente_postgrado'])
    ->name('dashboard');

// 📧 Vista previa del email (solo para desarrollo)
Route::get('/email-preview', function () {
    $name = 'Usuario de Prueba';
    $email = 'usuario@utalca.cl';
    $password = 'password123';
    
    return view('emails.welcome-user', compact('name', 'email', 'password'));
})->middleware(['auth', 'role:administrador,decano'])->name('email.preview');

// 🟡 Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // 🔍 Búsqueda Global
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // 📚 Clases
    Route::get('/clases/exportar', [ClaseController::class, 'exportar'])
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa,asistente_postgrado')
        ->name('clases.exportar');

    Route::resource('clases', ClaseController::class)
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa,asistente_postgrado');

    Route::get('/salas/disponibilidad', [ClaseController::class, 'disponibilidad'])
        ->middleware('role:administrador,director_administrativo,decano,asistente_programa')
        ->name('salas.disponibilidad');

    Route::get('/salas/horarios', [ClaseController::class, 'horariosDisponibles'])
        ->middleware('role:administrador,director_administrativo,decano,asistente_programa')
        ->name('salas.horarios');

    Route::get('/salas/disponibles', [ClaseController::class, 'salasDisponibles'])
        ->middleware('role:administrador,director_administrativo,decano,asistente_programa')
        ->name('salas.disponibles');

    // 📝 Sesiones de Clase
    Route::post('/clases/{clase}/sesiones', [App\Http\Controllers\ClaseSesionController::class, 'store'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('clases.sesiones.store');
    
    Route::put('/sesiones/{sesion}', [App\Http\Controllers\ClaseSesionController::class, 'update'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('sesiones.update');
    
    Route::patch('/sesiones/{sesion}/grabacion', [App\Http\Controllers\ClaseSesionController::class, 'updateGrabacion'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('sesiones.update-grabacion');
    
    Route::delete('/sesiones/{sesion}', [App\Http\Controllers\ClaseSesionController::class, 'destroy'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('sesiones.destroy');
    
    Route::post('/clases/{clase}/sesiones/generar', [App\Http\Controllers\ClaseSesionController::class, 'generarSesiones'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('clases.sesiones.generar');

    // 📅 Calendario
    Route::get('/calendario', [EventController::class, 'calendario'])
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa,asistente_postgrado')
        ->name('calendario');

    // 🎉 Eventos
    Route::get('/events', [EventController::class, 'index'])
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa,asistente_postgrado')
        ->name('events.index');
    Route::post('/events', [EventController::class, 'store'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('events.store');
    Route::put('/events/{event}', [EventController::class, 'update'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('events.destroy');

    // 🗂️ Incidencias
    Route::get('/incidencias/estadisticas', [IncidentController::class, 'estadisticas'])
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa,técnico,auxiliar,asistente_postgrado')
        ->name('incidencias.estadisticas');

    Route::get('/incidencias/exportar-pdf', [IncidentController::class, 'exportarPDF'])
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa,técnico,auxiliar,asistente_postgrado')
        ->name('incidencias.exportar.pdf');

    Route::resource('incidencias', IncidentController::class)
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa,técnico,auxiliar,asistente_postgrado')
        ->except(['update']);
    
    // Ruta específica para actualización con middleware de permisos
    Route::put('/incidencias/{incidencia}', [IncidentController::class, 'update'])
        ->middleware(['auth', 'incident.modify'])
        ->name('incidencias.update');

    // 📊 Estadísticas Generales (Analytics)
    Route::get('/analytics', [AnalyticsController::class, 'index'])
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_postgrado')
        ->name('analytics.index');
    
    Route::get('/analytics/api', [AnalyticsController::class, 'api'])
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_postgrado')
        ->name('analytics.api');

    // 🏛️ Salas
    Route::resource('rooms', RoomController::class)
        ->middleware('role:administrador,director_administrativo,decano,asistente_programa');

    // 👤 Perfil (todos los logueados pueden acceder a su perfil)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/foto', [ProfileController::class, 'updateFoto'])->name('profile.update-foto');
    Route::delete('/profile/foto', [ProfileController::class, 'deleteFoto'])->name('profile.delete-foto');
    Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::get('/profile/avatar', [ProfileController::class, 'getAvatar'])->name('profile.get-avatar');
    
    // 🔔 Notificaciones (tabla personalizada notifications)
    Route::patch('/notifications/{notification}/read', function($notificationId) {
        \DB::table('notifications')
            ->where('id', $notificationId)
            ->where('user_id', auth()->id())
            ->update(['read' => 1, 'updated_at' => now()]);
        return response()->json(['success' => true]);
    })->name('notifications.mark-read');

    // 👥 Usuarios (solo administrador y director administrativo pueden editar, decano solo ver)
    Route::resource('usuarios', UserController::class)
        ->middleware('role:administrador,director_administrativo,decano');

    // 📆 Periodos
    Route::resource('periods', PeriodController::class)
        ->middleware('role:administrador,director_administrativo,decano');
    Route::post('/periods/actualizar-proximo-anio', [PeriodController::class, 'actualizarAlProximoAnio'])
        ->middleware('role:administrador,director_administrativo')
        ->name('periods.actualizarProximoAnio');

    // 🎓 Cursos
    Route::resource('courses', CourseController::class)
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa');
    Route::delete('/courses/programa/{programa}', [CourseController::class, 'destroyPrograma'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa')
        ->name('courses.destroy-programa');

    // 📘 Magísteres
    Route::resource('magisters', MagisterController::class)
        ->except(['show'])
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa');

    // 👨‍🏫 Equipo
    Route::resource('equipo', StaffController::class)
        ->parameters(['equipo' => 'staff'])
        ->names([
            'index' => 'staff.index',
            'create' => 'staff.create',
            'store' => 'staff.store',
            'show' => 'staff.show',
            'edit' => 'staff.edit',
            'update' => 'staff.update',
            'destroy' => 'staff.destroy'
        ])
        ->middleware('role:administrador,director_administrativo,decano');
    
    Route::delete('/equipo/{staff}/foto', [StaffController::class, 'deleteFoto'])
        ->name('staff.delete-foto')
        ->middleware('role:administrador,director_administrativo');

    // 📰 Novedades
    Route::post('novedades/{novedad}/duplicate', [NovedadController::class, 'duplicate'])
        ->middleware('role:administrador,director_administrativo,asistente_postgrado')
        ->name('novedades.duplicate');
    
    Route::resource('novedades', NovedadController::class, [
        'parameters' => ['novedades' => 'novedad']
    ])->middleware('role:administrador,director_administrativo,decano,asistente_postgrado');

    // 🚨 Emergencias
    Route::post('/emergency', [EmergencyController::class, 'store'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('emergency.store');

    Route::resource('emergencies', EmergencyController::class)
        ->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa,asistente_postgrado');

    Route::patch('emergencies/{id}/deactivate', [EmergencyController::class, 'deactivate'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('emergencies.deactivate');

    Route::patch('emergencies/{id}/toggle-active', [EmergencyController::class, 'toggleActive'])
        ->middleware('role:administrador,director_administrativo,director_programa,asistente_programa,asistente_postgrado')
        ->name('emergencies.toggleActive');

    Route::resource('informes', InformeController::class)->middleware('role:administrador,director_administrativo,decano,director_programa,asistente_programa,asistente_postgrado');

    Route::get('informes/download/{id}', [InformeController::class, 'download'])->name('informes.download');

    
    // Reportes Diarios (nuevo sistema)
    Route::resource('daily-reports', App\Http\Controllers\DailyReportController::class)
        ->middleware('role:asistente_postgrado,decano');
    Route::get('daily-reports/download/{dailyReport}', [App\Http\Controllers\DailyReportController::class, 'download'])
        ->name('daily-reports.download')
        ->middleware('role:asistente_postgrado,decano');

});

require __DIR__.'/public.php';
require __DIR__.'/auth.php';


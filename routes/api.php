<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClaseController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DailyReportController;
use App\Http\Controllers\Api\EmergencyController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\InformeController;
use App\Http\Controllers\Api\MagisterController;
use App\Http\Controllers\Api\NovedadController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\UserController;

// 🌐 PREFIJO API + NOMBRE DE RUTAS
Route::name('api.')->group(function () {

    // 🔹 RUTAS PÚBLICAS (sin auth)
    Route::get('/trimestre-siguiente', function (Request $request) {
        $fecha = Carbon::parse($request->query('fecha'));
        $siguiente = Period::whereDate('fecha_inicio', '>', $fecha)
            ->orderBy('fecha_inicio')
            ->first();

        return response()->json([
            'fecha_inicio' => $siguiente?->fecha_inicio?->toDateString(),
            'anio' => $siguiente?->anio,
            'numero' => $siguiente?->numero,
            'error' => $siguiente ? null : 'No se encontró trimestre siguiente.',
        ]);
    })->name('trimestre-siguiente');

    Route::get('/trimestre-anterior', function (Request $request) {
        $fecha = Carbon::parse($request->query('fecha'));
        $anterior = Period::whereDate('fecha_fin', '<', $fecha)
            ->orderByDesc('fecha_fin')
            ->first();

        return response()->json([
            'fecha_inicio' => $anterior?->fecha_inicio?->toDateString(),
            'anio' => $anterior?->anio,
            'numero' => $anterior?->numero,
            'error' => $anterior ? null : 'No se encontró trimestre anterior.',
        ]);
    })->name('trimestre-anterior');

    Route::get('/periodo-por-fecha', function (Request $request) {
        $fecha = Carbon::parse($request->query('fecha'));
        $anioIngreso = $request->query('anio_ingreso');

        $query = Period::where('fecha_inicio', '<=', $fecha)
            ->where('fecha_fin', '>=', $fecha);

        if ($anioIngreso) {
            $query->where('anio_ingreso', $anioIngreso);
        }

        $periodo = $query->first();

        return response()->json(['periodo' => $periodo]);
    })->name('periodo-por-fecha');

    Route::get('/periodo-fecha-inicio', function (Request $request) {
        $anio = $request->query('anio');
        $trimestre = $request->query('trimestre');
        $anioIngreso = $request->query('anio_ingreso');

        $periodo = Period::where('anio', $anio)
            ->where('numero', $trimestre)
            ->when($anioIngreso, fn ($q) => $q->where('anio_ingreso', $anioIngreso))
            ->first();

        return response()->json([
            'fecha_inicio' => $periodo?->fecha_inicio?->toDateString(),
            'periodo' => $periodo,
        ]);
    })->name('periodo-fecha-inicio');

    Route::get('/trimestres-todos', function () {
        return Period::orderBy('fecha_inicio')->get(['fecha_inicio']);
    })->name('trimestres-todos');

    // ===== RUTAS PÚBLICAS (SIN AUTENTICACIÓN) =====
    Route::prefix('public')->group(function () {
        Route::get('magisters', [MagisterController::class, 'publicIndex']);
        Route::get('magisters-with-course-count', [MagisterController::class, 'publicMagistersWithCourseCount']);
        Route::get('magisters-with-courses', [CourseController::class, 'publicMagistersWithCourses']);
        Route::get('events', [EventController::class, 'publicIndex']);
        Route::get('staff', [StaffController::class, 'publicIndex']);
        Route::get('rooms', [RoomController::class, 'publicIndex']);
        Route::get('courses', [CourseController::class, 'publicIndex']);
        Route::get('clases', [ClaseController::class, 'publicIndex']);
        Route::get('clases/{id}', [ClaseController::class, 'publicShow']);
        Route::get('courses/years', [CourseController::class, 'publicAvailableYears']);
        Route::get('courses/magister/{magisterId}', [CourseController::class, 'publicCoursesByMagister']);
        Route::get('courses/magister/{magisterId}/paginated', [CourseController::class, 'publicCoursesByMagisterPaginated']);
        Route::get('novedades', [NovedadController::class, 'active']);
        Route::get('novedades/{id}', [NovedadController::class, 'show']);
        Route::get('informes', [InformeController::class, 'publicIndex']);
        Route::get('informes/{id}', [InformeController::class, 'publicShow']);
        Route::get('informes/{id}/download', [InformeController::class, 'publicDownload']);
    });

    // Emergencias
    Route::get('/emergencies/active', [EmergencyController::class, 'active'])->name('emergencies.active');

    // 🔐 AUTENTICACIÓN
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // 🔹 RUTAS PROTEGIDAS CON SANCTUM
    Route::middleware(['auth:sanctum'])->group(function () {

        // Usuario actual
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // 🔍 BÚSQUEDA GLOBAL
        Route::get('/search', [SearchController::class, 'search'])->name('search');

        Route::get('/profile', [AuthController::class, 'user'])->name('user.profile');

        // ===== ADMIN - Solo director_administrativo y decano =====
        Route::middleware('role.api:director_administrativo,decano')->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

            // Gestión de usuarios
            Route::apiResource('users', UserController::class)->names([
                'index' => 'users.index',
                'store' => 'users.store',
                'show' => 'users.show',
                'update' => 'users.update',
                'destroy' => 'users.destroy',
            ]);
            Route::get('users-statistics', [UserController::class, 'statistics'])->name('users.statistics');
        });

        // 🔹 RUTAS ESPECÍFICAS DE PERÍODOS (ANTES DEL APIRESOURCE)
        Route::put('/periods/update-to-next-year', [PeriodController::class, 'actualizarAlProximoAnio'])->name('periods.updateToNextYear');
        Route::post('/periods/trimestre-siguiente', [PeriodController::class, 'trimestreSiguiente'])->name('periods.trimestreSiguiente');
        Route::post('/periods/trimestre-anterior', [PeriodController::class, 'trimestreAnterior'])->name('periods.trimestreAnterior');
        Route::get('/periods/periodo-por-fecha/{fecha}', [PeriodController::class, 'periodoPorFecha'])->name('periods.periodoPorFecha');

        Route::get('clases/simple', [ClaseController::class, 'simple']);
        Route::get('clases/debug', [ClaseController::class, 'debug']);

        // Obtener magísteres con cursos agrupados
        // Rutas adicionales para cursos
        Route::get('courses/magisters-only', [CourseController::class, 'magistersOnly']);
        Route::get('courses/magisters', [CourseController::class, 'magistersWithCourses'])
            ->name('courses.magisters');
        Route::get('courses/magisters-list', [CourseController::class, 'magistersOnly']);
        Route::get('courses/magisters/{id}/courses', [CourseController::class, 'magisterCourses']);

        // ===== RECURSOS API CON CONTROL DE ROLES =====
        
        // Staff/Nuestro Equipo - Todos ven, solo director_administrativo y decano pueden crear/editar/eliminar
        Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
        Route::get('staff/{staff}', [StaffController::class, 'show'])->name('staff.show');
        Route::middleware('role.api:director_administrativo,decano')->group(function () {
            Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
            Route::put('staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
            Route::delete('staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
        });

        // Rooms/Salas - Todos ven, solo director_administrativo, asistente_programa y decano pueden crear/editar/eliminar
        Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
        Route::middleware('role.api:director_administrativo,asistente_programa,decano')->group(function () {
            Route::post('rooms', [RoomController::class, 'store'])->name('rooms.store');
            Route::put('rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
            Route::delete('rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
        });

        // Periods/Períodos - Todos ven, solo director_administrativo y decano pueden crear/editar/eliminar
        Route::get('periods', [PeriodController::class, 'index'])->name('periods.index');
        Route::get('periods/{period}', [PeriodController::class, 'show'])->name('periods.show');
        Route::middleware('role.api:director_administrativo,decano')->group(function () {
            Route::post('periods', [PeriodController::class, 'store'])->name('periods.store');
            Route::put('periods/{period}', [PeriodController::class, 'update'])->name('periods.update');
            Route::delete('periods/{period}', [PeriodController::class, 'destroy'])->name('periods.destroy');
        });

        // Magisters/Programas - Todos ven, solo director_administrativo y decano pueden crear/editar/eliminar
        Route::get('magisters', [MagisterController::class, 'index'])->name('magisters.index');
        Route::get('magisters/{magister}', [MagisterController::class, 'show'])->name('magisters.show');
        Route::middleware('role.api:director_administrativo,decano')->group(function () {
            Route::post('magisters', [MagisterController::class, 'store'])->name('magisters.store');
            Route::put('magisters/{magister}', [MagisterController::class, 'update'])->name('magisters.update');
            Route::delete('magisters/{magister}', [MagisterController::class, 'destroy'])->name('magisters.destroy');
        });

        // Incidents/Incidencias - Todos ven, varios roles pueden crear/modificar
        Route::get('incidents', [IncidentController::class, 'index'])->name('incidents.index');
        Route::get('incidents/{incident}', [IncidentController::class, 'show'])->name('incidents.show');
        Route::get('incidents-statistics', [IncidentController::class, 'estadisticas'])->name('incidents.statistics');
        Route::middleware('role.api:director_administrativo,director_programa,asistente_programa,técnico,auxiliar,decano,asistente_postgrado')->group(function () {
            Route::post('incidents', [IncidentController::class, 'store'])->name('incidents.store');
            Route::put('incidents/{incident}', [IncidentController::class, 'update'])->name('incidents.update');
            Route::delete('incidents/{incident}', [IncidentController::class, 'destroy'])->name('incidents.destroy');
        });

        // Courses/Módulos - Todos ven, solo director_administrativo, director_programa, asistente_programa y decano pueden crear/editar/eliminar
        Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');
        Route::middleware('role.api:director_administrativo,director_programa,asistente_programa,decano')->group(function () {
            Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
            Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
            Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        });

        // Daily Reports API - Solo asistente_postgrado y decano
        Route::middleware('role.api:asistente_postgrado,decano')->group(function () {
            Route::apiResource('daily-reports', DailyReportController::class)->names([
                'index' => 'daily-reports.index',
                'store' => 'daily-reports.store',
                'show' => 'daily-reports.show',
                'update' => 'daily-reports.update',
                'destroy' => 'daily-reports.destroy',
            ]);

            // Rutas adicionales para Daily Reports
            Route::get('daily-reports/{dailyReport}/download-pdf', [DailyReportController::class, 'downloadPdf'])->name('daily-reports.download-pdf');
            Route::get('daily-reports-statistics', [DailyReportController::class, 'statistics'])->name('daily-reports.statistics');
            Route::get('daily-reports-resources', [DailyReportController::class, 'resources'])->name('daily-reports.resources');
        });

        // Informes/Archivos - Todos ven, solo director_administrativo, director_programa, asistente_programa, decano, asistente_postgrado pueden crear/editar/eliminar
        Route::get('informes', [InformeController::class, 'index'])->name('informes.index');
        Route::get('informes/{informe}', [InformeController::class, 'show'])->name('informes.show');
        Route::get('informes/{informe}/download', [InformeController::class, 'download'])->name('informes.download');
        Route::get('informes-statistics', [InformeController::class, 'statistics'])->name('informes.statistics');
        Route::get('informes-resources', [InformeController::class, 'resources'])->name('informes.resources');
        Route::middleware('role.api:director_administrativo,director_programa,asistente_programa,decano,asistente_postgrado')->group(function () {
            Route::post('informes', [InformeController::class, 'store'])->name('informes.store');
            Route::put('informes/{informe}', [InformeController::class, 'update'])->name('informes.update');
            Route::delete('informes/{informe}', [InformeController::class, 'destroy'])->name('informes.destroy');
        });

        // Novedades API - Solo director_administrativo, decano, asistente_postgrado
        Route::middleware('role.api:director_administrativo,decano,asistente_postgrado')->group(function () {
            Route::apiResource('novedades', NovedadController::class)->names([
                'index' => 'novedades.index',
                'store' => 'novedades.store',
                'show' => 'novedades.show',
                'update' => 'novedades.update',
                'destroy' => 'novedades.destroy',
            ]);

            // Rutas adicionales para Novedades
            Route::get('novedades-statistics', [NovedadController::class, 'statistics'])->name('novedades.statistics');
            Route::get('novedades-resources', [NovedadController::class, 'resources'])->name('novedades.resources');
        });

        // Clases - Todos ven, director_administrativo, director_programa, asistente_programa, decano, asistente_postgrado pueden crear/editar/eliminar
        Route::get('clases', [ClaseController::class, 'index'])->name('clases.index');
        Route::get('clases/{clase}', [ClaseController::class, 'show'])->name('clases.show');
        Route::get('clases-resources', [ClaseController::class, 'resources'])->name('clases.resources');
        Route::get('salas/disponibilidad', [ClaseController::class, 'disponibilidad'])->name('salas.disponibilidad');
        Route::get('salas/horarios', [ClaseController::class, 'horarios'])->name('salas.horarios');
        Route::get('salas/disponibles', [ClaseController::class, 'salasDisponibles'])->name('salas.disponibles');
        Route::middleware('role.api:director_administrativo,director_programa,asistente_programa,decano,asistente_postgrado')->group(function () {
            Route::post('clases', [ClaseController::class, 'store'])->name('clases.store');
            Route::put('clases/{clase}', [ClaseController::class, 'update'])->name('clases.update');
            Route::delete('clases/{clase}', [ClaseController::class, 'destroy'])->name('clases.destroy');
        });

        // Events/Eventos - Todos ven, director_administrativo, director_programa, asistente_programa, decano, asistente_postgrado, docente pueden crear/editar/eliminar
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/calendario', [EventController::class, 'calendario'])->name('calendario.mobile');
        Route::middleware('role.api:director_administrativo,director_programa,asistente_programa,decano,asistente_postgrado,docente')->group(function () {
            Route::post('/events', [EventController::class, 'store'])->name('events.store');
            Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
            Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        });

        // Emergencies/Emergencias - Todos ven, director_administrativo, director_programa, asistente_programa, decano, asistente_postgrado pueden crear/editar/eliminar
        Route::get('/emergencies', [EmergencyController::class, 'index'])->name('emergencies.index');
        Route::middleware('role.api:director_administrativo,director_programa,asistente_programa,decano,asistente_postgrado')->group(function () {
            Route::post('/emergencies', [EmergencyController::class, 'store'])->name('emergencies.store');
            Route::put('/emergencies/{id}', [EmergencyController::class, 'update'])->name('emergencies.update');
            Route::delete('/emergencies/{id}', [EmergencyController::class, 'destroy'])->name('emergencies.destroy');
            Route::patch('/emergencies/{id}/deactivate', [EmergencyController::class, 'deactivate'])->name('emergencies.deactivate');
        });

        // 📊 ANALYTICS/ESTADÍSTICAS - Solo director_administrativo, decano, director_programa, asistente_postgrado
        Route::middleware('role.api:director_administrativo,decano,director_programa,asistente_postgrado')->group(function () {
            Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
            Route::get('/analytics/period-stats', [AnalyticsController::class, 'periodStats'])->name('analytics.period-stats');
        });
    });

});

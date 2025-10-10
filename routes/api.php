<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClaseController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EmergencyController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\IncidentController;
use App\Http\Controllers\Api\MagisterController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\StaffController;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

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
        $cohorte = $request->query('cohorte');
        
        $query = Period::where('fecha_inicio', '<=', $fecha)
            ->where('fecha_fin', '>=', $fecha);
        
        if ($cohorte) {
            $query->where('cohorte', $cohorte);
        }
        
        $periodo = $query->first();

        return response()->json(['periodo' => $periodo]);
    })->name('periodo-por-fecha');

    Route::get('/periodo-fecha-inicio', function (Request $request) {
        $anio = $request->query('anio');
        $trimestre = $request->query('trimestre');
        $cohorte = $request->query('cohorte');

        $periodo = Period::where('anio', $anio)
            ->where('numero', $trimestre)
            ->when($cohorte, fn($q) => $q->where('cohorte', $cohorte))
            ->first();

        return response()->json([
            'fecha_inicio' => $periodo?->fecha_inicio?->toDateString(),
            'periodo' => $periodo
        ]);
    })->name('periodo-fecha-inicio');

    Route::get('/trimestres-todos', function () {
        return Period::orderBy('fecha_inicio')->get(['fecha_inicio']);
    })->name('trimestres-todos');

    // ===== RUTAS PÚBLICAS (SIN AUTENTICACIÓN) =====
    Route::prefix('public')->group(function () {
        Route::get('magisters', [MagisterController::class, 'publicIndex']);
        Route::get('magisters-with-course-count', [MagisterController::class, 'publicMagistersWithCourseCount']);
        Route::get('events', [EventController::class, 'publicIndex']);
        Route::get('staff', [StaffController::class, 'publicIndex']);
        Route::get('rooms', [RoomController::class, 'publicIndex']);
        Route::get('courses', [CourseController::class, 'publicIndex']);
        Route::get('courses/magister/{magisterId}', [CourseController::class, 'publicCoursesByMagister']);
        Route::get('courses/magister/{magisterId}/paginated', [CourseController::class, 'publicCoursesByMagisterPaginated']);
    });

    // �� AUTENTICACIÓN
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

        // ADMIN
        Route::middleware('role.api:admin')->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        });

        // USUARIO

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

        // �� RECURSOS API CON NOMBRES ÚNICOS
        Route::apiResource('staff', StaffController::class)->names([
            'index' => 'staff.index',
            'store' => 'staff.store',
            'show' => 'staff.show',
            'update' => 'staff.update',
            'destroy' => 'staff.destroy',
        ]);

        Route::apiResource('rooms', RoomController::class)->names([
            'index' => 'rooms.index',
            'store' => 'rooms.store',
            'show' => 'rooms.show',
            'update' => 'rooms.update',
            'destroy' => 'rooms.destroy',
        ]);

        // ⚠️ ESTA RUTA DEBE IR DESPUÉS DE LAS RUTAS ESPECÍFICAS
        Route::apiResource('periods', PeriodController::class)->names([
            'index' => 'periods.index',
            'store' => 'periods.store',
            'show' => 'periods.show',
            'update' => 'periods.update',
            'destroy' => 'periods.destroy',
        ]);

        Route::apiResource('magisters', MagisterController::class)->names([
            'index' => 'magisters.index',
            'store' => 'magisters.store',
            'show' => 'magisters.show',
            'update' => 'magisters.update',
            'destroy' => 'magisters.destroy',
        ]);

        Route::apiResource('incidents', IncidentController::class)->names([
            'index' => 'incidents.index',
            'store' => 'incidents.store',
            'show' => 'incidents.show',
            'update' => 'incidents.update',
            'destroy' => 'incidents.destroy',
        ]);

        Route::apiResource('courses', CourseController::class)->names([
            'index' => 'courses.index',
            'store' => 'courses.store',
            'show' => 'courses.show',
            'update' => 'courses.update',
            'destroy' => 'courses.destroy',
        ]);

        Route::apiResource('clases', ClaseController::class)->names([
            'index' => 'clases.index',
            'store' => 'clases.store',
            'show' => 'clases.show',
            'update' => 'clases.update',
            'destroy' => 'clases.destroy',
        ]);

        // 🔹 EVENTOS
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::get('/calendario', [EventController::class, 'calendario'])->name('calendario.mobile');
        // 🔹 EMERGENCIAS
        Route::get('/emergencies', [EmergencyController::class, 'index'])->name('emergencies.index');
        Route::post('/emergencies', [EmergencyController::class, 'store'])->name('emergencies.store');
        Route::put('/emergencies/{id}', [EmergencyController::class, 'update'])->name('emergencies.update');
        Route::delete('/emergencies/{id}', [EmergencyController::class, 'destroy'])->name('emergencies.destroy');
        Route::patch('/emergencies/{id}/deactivate', [EmergencyController::class, 'deactivate'])->name('emergencies.deactivate');
        Route::get('/emergencies/active', [EmergencyController::class, 'active'])->name('emergencies.active');
    });

});

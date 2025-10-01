<?php

<<<<<<< Updated upstream
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MagisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;
use App\Models\Incident;
use App\Http\Controllers\CloudinaryTestController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\DashboardController;

// Página de inicio pública
Route::get('/', function () {
    return view('welcome');
});

// Dashboard con datos
Route::get('/dashboard', function () {
    $total = Incident::count();
    $pendientes = Incident::where('estado', 'pendiente')->count();
    $resueltas = Incident::where('estado', 'resuelta')->count();
    $ultimas = Incident::latest()->take(5)->get();

    return view('dashboard', compact('total', 'pendientes', 'resueltas', 'ultimas'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
=======
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
    ->middleware(['auth', 'verified', 'role:administrador'])
    ->name('dashboard');

// 🟡 Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // 📚 Clases
    Route::get('/clases/exportar', [ClaseController::class, 'exportar'])
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado')
        ->name('clases.exportar');

    Route::resource('clases', ClaseController::class)
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado');

    Route::get('/salas/disponibilidad', [ClaseController::class, 'disponibilidad'])
        ->middleware('role:administrador,asistente_programa')
        ->name('salas.disponibilidad');

    Route::get('/salas/horarios', [ClaseController::class, 'horariosDisponibles'])
        ->middleware('role:administrador,asistente_programa')
        ->name('salas.horarios');
>>>>>>> Stashed changes

    // 📅 Calendario
    Route::get('/calendario', [EventController::class, 'calendario'])
        ->middleware('role:administrador,director_programa,asistente_programa,asistente_postgrado')
        ->name('calendario');

<<<<<<< Updated upstream
    // Eventos
    Route::resource('events', EventController::class)->except(['create', 'edit', 'show']);

    // Calendario
    Route::view('/calendario', 'calendario.index')->name('calendario');

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

Route::get('/cloudinary-test', [CloudinaryTestController::class, 'form'])->name('cloudinary.form');
Route::post('/cloudinary-test', [CloudinaryTestController::class, 'upload'])->name('cloudinary.upload');

require __DIR__ . '/auth.php';

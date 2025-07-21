<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;
use App\Models\Incident;
use App\Http\Controllers\CloudinaryTestController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RoomController;

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

    // Incidencias
    Route::get('/incidencias/estadisticas', [IncidentController::class, 'estadisticas'])->name('incidencias.estadisticas');
    Route::get('/incidencias/exportar-pdf', [IncidentController::class, 'exportarPDF'])->name('incidencias.exportar.pdf');
    Route::resource('incidencias', IncidentController::class);

    // Eventos
    Route::resource('events', EventController::class)->except(['create', 'edit', 'show']);

    // Calendario
    Route::view('/calendario', 'calendario.index')->name('calendario');

    // Salas
    Route::resource('rooms', RoomController::class);

});

Route::get('/cloudinary-test', [CloudinaryTestController::class, 'form'])->name('cloudinary.form');
Route::post('/cloudinary-test', [CloudinaryTestController::class, 'upload'])->name('cloudinary.upload');

require __DIR__ . '/auth.php';

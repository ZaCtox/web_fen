<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;
use Illuminate\Support\Facades\Route;
use App\Models\Incident;

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
});

require __DIR__ . '/auth.php';

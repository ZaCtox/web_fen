<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // ✅ Esto es clave
use App\Models\Period;
use Illuminate\Support\Carbon;



Route::get('/proximo-periodo', function (Request $request) {
    $desde = Carbon::parse($request->query('desde'));

    $proximo = Period::whereDate('fecha_inicio', '>', $desde)
        ->orderBy('fecha_inicio')
        ->first();

    return response()->json([
        'fecha_inicio' => $proximo?->fecha_inicio?->toDateString(),
        'anio' => $proximo?->anio,
        'numero' => $proximo?->numero,
    ]);
});

Route::get('/periodo-anterior', function (Request $request) {
    $desde = Carbon::parse($request->query('desde'));

    $anterior = Period::whereDate('fecha_inicio', '<', $desde)
        ->orderByDesc('fecha_inicio')
        ->first();

    return response()->json([
        'fecha_inicio' => $anterior?->fecha_inicio?->toDateString(),
        'anio' => $anterior?->anio,
        'numero' => $anterior?->numero,
    ]);
});



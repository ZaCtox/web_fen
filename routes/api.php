<?php

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Models\Period;

Route::get('/trimestre-siguiente', function (Request $request) {
    $fecha = Carbon::parse($request->query('fecha'));

    $siguiente = Period::whereDate('fecha_inicio', '>', $fecha)
        ->orderBy('fecha_inicio')
        ->first();

    return response()->json([
        'fecha_inicio' => $siguiente?->fecha_inicio?->toDateString(),
        'anio' => $siguiente?->anio,
        'numero' => $siguiente?->numero,
        'error' => $siguiente ? null : 'No se encontró trimestre siguiente.'
    ]);
});

Route::get('/trimestre-anterior', function (Request $request) {
    $fecha = Carbon::parse($request->query('fecha'));

    $anterior = Period::whereDate('fecha_fin', '<', $fecha)
        ->orderByDesc('fecha_fin')
        ->first();

    return response()->json([
        'fecha_inicio' => $anterior?->fecha_inicio?->toDateString(),
        'anio' => $anterior?->anio,
        'numero' => $anterior?->numero,
        'error' => $anterior ? null : 'No se encontró trimestre anterior.'
    ]);
});

Route::get('/periodo-por-fecha', function (Request $request) {
    $fecha = Carbon::parse($request->query('fecha'));

    $periodo = Period::where('fecha_inicio', '<=', $fecha)
        ->where('fecha_fin', '>=', $fecha)
        ->first();

    return response()->json(['periodo' => $periodo]);
});

Route::get('/trimestres-todos', function () {
    return \App\Models\Period::orderBy('fecha_inicio')->get(['fecha_inicio']);
});
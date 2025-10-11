<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Simular la query del API con los parámetros del calendario
$magisterId = 1;
$cohorte = '2025-2026';
$rangeStart = \Carbon\Carbon::parse('2025-10-01');
$rangeEnd = \Carbon\Carbon::parse('2025-10-31');

echo "=== SIMULACIÓN DE QUERY API ===\n\n";
echo "Parámetros:\n";
echo "  Magíster ID: " . $magisterId . "\n";
echo "  Cohorte: " . $cohorte . "\n";
echo "  Rango: " . $rangeStart->toDateString() . " a " . $rangeEnd->toDateString() . "\n\n";

$sesiones = App\Models\ClaseSesion::with(['clase.room', 'clase.period', 'clase.course.magister'])
    ->whereBetween('fecha', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
    ->when(!empty($magisterId), fn($q) => $q->whereHas('clase.course', fn($qq) => $qq->where('magister_id', $magisterId)))
    ->when(!empty($cohorte), fn($q) => $q->whereHas('clase.period', fn($qq) => $qq->where('cohorte', $cohorte)))
    ->get();

echo "✅ Sesiones encontradas en octubre 2025: " . $sesiones->count() . "\n\n";

if ($sesiones->count() > 0) {
    echo "📋 Detalle de sesiones:\n";
    foreach ($sesiones as $s) {
        echo "  - " . $s->fecha . " | " . $s->clase->course->nombre . "\n";
    }
} else {
    echo "❌ No se encontraron sesiones\n";
    echo "\nProbando sin filtro de cohorte:\n";
    
    $sesionesSinCohorte = App\Models\ClaseSesion::with(['clase.room', 'clase.period', 'clase.course.magister'])
        ->whereBetween('fecha', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
        ->get();
    
    echo "Sesiones sin filtro de cohorte: " . $sesionesSinCohorte->count() . "\n";
}



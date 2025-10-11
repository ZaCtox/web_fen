<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DE DATOS ===\n\n";

// Verificar sesión
$sesion = App\Models\ClaseSesion::first();
if ($sesion) {
    echo "✅ Sesión ID: " . $sesion->id . "\n";
    echo "   Clase ID: " . $sesion->clase_id . "\n";
    echo "   Fecha: " . $sesion->fecha . "\n";
    echo "   Observaciones: " . substr($sesion->observaciones, 0, 100) . "...\n\n";
    
    // Verificar relación con clase
    if ($sesion->clase) {
        echo "✅ Clase relacionada ID: " . $sesion->clase->id . "\n";
        echo "   Course ID: " . $sesion->clase->course_id . "\n";
        echo "   Period ID: " . $sesion->clase->period_id . "\n\n";
        
        // Verificar período
        if ($sesion->clase->period) {
            echo "✅ Período relacionado ID: " . $sesion->clase->period->id . "\n";
            echo "   Cohorte: " . ($sesion->clase->period->cohorte ?? 'NULL') . "\n";
            echo "   Año: " . $sesion->clase->period->anio . "\n";
            echo "   Número: " . $sesion->clase->period->numero . "\n\n";
        } else {
            echo "❌ No hay período relacionado\n\n";
        }
        
        // Verificar curso y magíster
        if ($sesion->clase->course) {
            echo "✅ Curso relacionado: " . $sesion->clase->course->nombre . "\n";
            if ($sesion->clase->course->magister) {
                echo "   Magíster: " . $sesion->clase->course->magister->nombre . "\n";
                echo "   Magíster ID: " . $sesion->clase->course->magister->id . "\n\n";
            }
        }
    } else {
        echo "❌ No hay clase relacionada\n\n";
    }
} else {
    echo "❌ No hay sesiones en la base de datos\n";
}

// Contar sesiones con cohorte 2025-2026
$sesionesCohorte = App\Models\ClaseSesion::whereHas('clase.period', function($q) {
    $q->where('cohorte', '2025-2026');
})->count();

echo "📊 Total sesiones con cohorte 2025-2026: " . $sesionesCohorte . "\n";

// Verificar las fechas de las sesiones
$sesiones = App\Models\ClaseSesion::orderBy('fecha')->get();
echo "\n📅 Fechas de las sesiones:\n";
foreach ($sesiones as $s) {
    echo "   - " . $s->fecha . " (Clase ID: " . $s->clase_id . ")\n";
}



<?php

use App\Models\User;
use App\Models\Magister;
use App\Models\Course;
use App\Models\Period;
use App\Models\Room;
use App\Models\Clase;
use App\Models\Event;
use App\Models\Incident;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===== TESTER DE DATOS CARGADOS =====\n";

function ok($label, $condition) {
    echo $condition ? "✅ $label\n" : "❌ $label\n";
}

try {
    // Usuarios
    ok("Usuarios cargados", User::count() >= 2);
    ok("Usuario admin existe", User::where('email', 'admin@webfen.cl')->exists());

    // Magísteres
    ok("Magísteres cargados", Magister::count() >= 4);

    // Periodos
    ok("Periodos cargados", Period::count() >= 6);

    // Cursos
    ok("Cursos con magíster y periodo", Course::has('magister')->has('period')->count() > 0);

    // Salas
    ok("Salas cargadas", Room::count() >= 4);

    // Clases
    $clase = Clase::with(['course', 'period', 'room'])->first();
    ok("Clases creadas", Clase::count() > 0);
    ok("Clase tiene relaciones válidas", $clase && $clase->course && $clase->period);

    // Eventos
    ok("Eventos creados", Event::count() > 0);

    // Incidencias
    $incidencia = Incident::with('room')->latest()->first();
    ok("Incidencias cargadas", Incident::count() > 0);
    ok("Incidencia tiene sala", $incidencia && $incidencia->room);

    echo "\n🚀 Todos los datos básicos fueron verificados.\n";

} catch (Exception $e) {
    echo "💥 Error: " . $e->getMessage() . "\n";
}

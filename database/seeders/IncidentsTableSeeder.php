<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;
use App\Models\Room;
use App\Models\Period;
use Illuminate\Support\Carbon;

class IncidentsTableSeeder extends Seeder
{
    public function run()
    {
        // No se crean incidencias iniciales
        // Los usuarios las crearán según sea necesario
        $this->command->info('✅ Seeder de incidencias ejecutado (sin datos iniciales - listo para que usuarios creen incidencias).');
    }
}

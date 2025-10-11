<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Magister;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // No se crean eventos iniciales
        // Los usuarios los crearán según sea necesario
        $this->command->info('✅ Seeder de eventos ejecutado (sin datos iniciales - listo para que usuarios creen eventos).');
    }
}

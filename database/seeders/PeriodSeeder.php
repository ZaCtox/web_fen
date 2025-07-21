<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Period;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = [
            ['anio' => 1, 'numero' => 1, 'fecha_inicio' => '2025-03-01', 'fecha_fin' => '2025-05-31'],
            ['anio' => 1, 'numero' => 2, 'fecha_inicio' => '2025-06-01', 'fecha_fin' => '2025-08-31'],
            ['anio' => 1, 'numero' => 3, 'fecha_inicio' => '2025-09-01', 'fecha_fin' => '2025-11-30'],
            ['anio' => 2, 'numero' => 1, 'fecha_inicio' => '2026-03-01', 'fecha_fin' => '2026-05-31'],
            ['anio' => 2, 'numero' => 2, 'fecha_inicio' => '2026-06-01', 'fecha_fin' => '2026-08-31'],
            ['anio' => 2, 'numero' => 3, 'fecha_inicio' => '2026-09-01', 'fecha_fin' => '2026-11-30'],
        ];

        foreach ($periodos as $data) {
            Period::create(array_merge($data, ['activo' => true]));
        }

        $this->command->info("Periodos académicos cargados correctamente.");
    }
}

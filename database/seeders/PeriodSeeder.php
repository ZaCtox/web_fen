<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Period;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = [
            // ========================================
            // cohorte 2024-2025 (PASADO)
            // ========================================
            ['cohorte' => '2024-2025', 'anio' => 1, 'numero' => 1, 'fecha_inicio' => '2024-03-08', 'fecha_fin' => '2024-06-01'],
            ['cohorte' => '2024-2025', 'anio' => 1, 'numero' => 2, 'fecha_inicio' => '2024-06-07', 'fecha_fin' => '2024-08-31'],
            ['cohorte' => '2024-2025', 'anio' => 1, 'numero' => 3, 'fecha_inicio' => '2024-09-06', 'fecha_fin' => '2024-12-07'],
            ['cohorte' => '2024-2025', 'anio' => 2, 'numero' => 4, 'fecha_inicio' => '2025-03-08', 'fecha_fin' => '2025-05-31'],
            ['cohorte' => '2024-2025', 'anio' => 2, 'numero' => 5, 'fecha_inicio' => '2025-06-06', 'fecha_fin' => '2025-08-30'],
            ['cohorte' => '2024-2025', 'anio' => 2, 'numero' => 6, 'fecha_inicio' => '2025-09-05', 'fecha_fin' => '2025-12-06'],
            
            // ========================================
            // cohorte 2025-2026 (ACTUAL)
            // ========================================
            ['cohorte' => '2025-2026', 'anio' => 1, 'numero' => 1, 'fecha_inicio' => '2025-03-07', 'fecha_fin' => '2025-05-31'],
            ['cohorte' => '2025-2026', 'anio' => 1, 'numero' => 2, 'fecha_inicio' => '2025-06-06', 'fecha_fin' => '2025-08-30'],
            ['cohorte' => '2025-2026', 'anio' => 1, 'numero' => 3, 'fecha_inicio' => '2025-09-05', 'fecha_fin' => '2025-12-06'],
            ['cohorte' => '2025-2026', 'anio' => 2, 'numero' => 4, 'fecha_inicio' => '2026-03-07', 'fecha_fin' => '2026-05-30'],
            ['cohorte' => '2025-2026', 'anio' => 2, 'numero' => 5, 'fecha_inicio' => '2026-06-05', 'fecha_fin' => '2026-08-29'],
            ['cohorte' => '2025-2026', 'anio' => 2, 'numero' => 6, 'fecha_inicio' => '2026-09-04', 'fecha_fin' => '2026-12-05'],
        ];

        foreach ($periodos as $periodo) {
            Period::create($periodo);
        }

        $this->command->info("Periodos académicos cargados correctamente.");
    }
}



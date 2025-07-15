<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trimestre;

class TrimestreSeeder extends Seeder
{
    public function run(): void
    {
        $trimestres = [
            // Año 2025
            [
                'nombre' => 'Trimestre 1',
                'año' => 2025,
                'numero' => 1,
                'fecha_inicio' => '2025-03-04',
                'fecha_fin' => '2025-05-24',
            ],
            [
                'nombre' => 'Trimestre 2',
                'año' => 2025,
                'numero' => 2,
                'fecha_inicio' => '2025-06-03',
                'fecha_fin' => '2025-08-23',
            ],
            [
                'nombre' => 'Trimestre 3',
                'año' => 2025,
                'numero' => 3,
                'fecha_inicio' => '2025-09-09',
                'fecha_fin' => '2025-11-30',
            ],
            // Año 2026 (respetando mismo patrón)
            [
                'nombre' => 'Trimestre 1',
                'año' => 2026,
                'numero' => 1,
                'fecha_inicio' => '2026-03-03', // primer martes de marzo
                'fecha_fin' => '2026-05-23',
            ],
            [
                'nombre' => 'Trimestre 2',
                'año' => 2026,
                'numero' => 2,
                'fecha_inicio' => '2026-06-02',
                'fecha_fin' => '2026-08-22',
            ],
            [
                'nombre' => 'Trimestre 3',
                'año' => 2026,
                'numero' => 3,
                'fecha_inicio' => '2026-09-08',
                'fecha_fin' => '2026-11-28',
            ],
        ];

        foreach ($trimestres as $t) {
            Trimestre::create([
                ...$t,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MallaCurricular;
use App\Models\Magister;

class MallasCurricularesSeeder extends Seeder
{
    public function run()
    {
        // Obtener el magíster de Gestión de Sistemas de Salud
        $gestionSalud = Magister::where('nombre', 'Gestión de Sistemas de Salud')->first();

        if (!$gestionSalud) {
            $this->command->warn('⚠️ No se encontró el Magíster en Gestión de Sistemas de Salud.');
            return;
        }

        $mallas = [
            // Malla pasada (2024-2025)
            [
                'magister_id' => $gestionSalud->id,
                'nombre' => 'Malla Curricular 2024-2025',
                'codigo' => 'GSS-2024-V1',
                'año_inicio' => 2024,
                'año_fin' => 2025,
                'activa' => false,
                'descripcion' => 'Malla curricular para la cohorte 2024-2025 del Magíster en Gestión de Sistemas de Salud.'
            ],
            // Malla actual (2025-2026)
            [
                'magister_id' => $gestionSalud->id,
                'nombre' => 'Malla Curricular 2025-2026',
                'codigo' => 'GSS-2025-V2',
                'año_inicio' => 2025,
                'año_fin' => 2026,
                'activa' => true,
                'descripcion' => 'Malla curricular vigente con énfasis en transformación digital, gestión hospitalaria moderna y políticas públicas en salud.'
            ],
        ];

        foreach ($mallas as $mallaData) {
            MallaCurricular::firstOrCreate(
                ['codigo' => $mallaData['codigo']],
                $mallaData
            );
        }

        $this->command->info('✅ Mallas curriculares del Magíster en Gestión de Sistemas de Salud creadas correctamente.');
    }
}

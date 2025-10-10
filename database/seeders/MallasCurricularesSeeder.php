<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MallaCurricular;
use App\Models\Magister;

class MallasCurricularesSeeder extends Seeder
{
    public function run()
    {
        // Obtener los magísteres
        $gestionSalud = Magister::where('nombre', 'Gestión de Sistemas de Salud')->first();
        $economia = Magister::where('nombre', 'Economía')->first();
        $tributaria = Magister::where('nombre', 'Dirección y Planificación Tributaria')->first();
        $politicas = Magister::where('nombre', 'Gestión y Políticas Públicas')->first();

        $mallas = [];

        // Mallas para Gestión de Sistemas de Salud
        if ($gestionSalud) {
            $mallas[] = [
                'magister_id' => $gestionSalud->id,
                'nombre' => 'Malla Curricular 2024-2025',
                'codigo' => 'GSS-2024-V1',
                'año_inicio' => 2024,
                'año_fin' => 2025,
                'activa' => false, // Pasada
                'descripcion' => 'Primera versión de la malla curricular para Gestión de Sistemas de Salud, vigente para estudiantes que ingresaron en 2024-2025.'
            ];

            $mallas[] = [
                'magister_id' => $gestionSalud->id,
                'nombre' => 'Malla Curricular 2025-2026',
                'codigo' => 'GSS-2025-V2',
                'año_inicio' => 2025,
                'año_fin' => 2026,
                'activa' => true, // Actual
                'descripcion' => 'Segunda versión de la malla curricular con énfasis en transformación digital y gestión hospitalaria moderna.'
            ];
        }

        // Mallas para Economía
        if ($economia) {
            $mallas[] = [
                'magister_id' => $economia->id,
                'nombre' => 'Malla Curricular 2024-2025',
                'codigo' => 'ECO-2024-V1',
                'año_inicio' => 2024,
                'año_fin' => 2025,
                'activa' => false, // Pasada
                'descripcion' => 'Malla curricular enfocada en economía aplicada y análisis cuantitativo.'
            ];

            $mallas[] = [
                'magister_id' => $economia->id,
                'nombre' => 'Malla Curricular 2025-2026',
                'codigo' => 'ECO-2025-V2',
                'año_inicio' => 2025,
                'año_fin' => 2026,
                'activa' => true, // Actual
                'descripcion' => 'Actualización con mayor énfasis en economía digital y análisis de big data.'
            ];
        }

        // Mallas para Dirección y Planificación Tributaria
        if ($tributaria) {
            $mallas[] = [
                'magister_id' => $tributaria->id,
                'nombre' => 'Malla Curricular 2024-2025',
                'codigo' => 'DPT-2024-V1',
                'año_inicio' => 2024,
                'año_fin' => 2025,
                'activa' => false, // Pasada
                'descripcion' => 'Malla curricular con enfoque en legislación tributaria chilena y planificación fiscal.'
            ];

            $mallas[] = [
                'magister_id' => $tributaria->id,
                'nombre' => 'Malla Curricular 2025-2026',
                'codigo' => 'DPT-2025-V2',
                'año_inicio' => 2025,
                'año_fin' => 2026,
                'activa' => true, // Actual
                'descripcion' => 'Actualización con incorporación de tributación internacional y comercio electrónico.'
            ];
        }

        // Mallas para Gestión y Políticas Públicas
        if ($politicas) {
            $mallas[] = [
                'magister_id' => $politicas->id,
                'nombre' => 'Malla Curricular 2024-2025',
                'codigo' => 'GPP-2024-V1',
                'año_inicio' => 2024,
                'año_fin' => 2025,
                'activa' => false, // Pasada
                'descripcion' => 'Malla curricular orientada a la gestión pública moderna y evaluación de políticas.'
            ];

            $mallas[] = [
                'magister_id' => $politicas->id,
                'nombre' => 'Malla Curricular 2025-2026',
                'codigo' => 'GPP-2025-V2',
                'año_inicio' => 2025,
                'año_fin' => 2026,
                'activa' => true, // Actual
                'descripcion' => 'Versión actualizada con énfasis en transformación digital del Estado y gobernanza.'
            ];
        }

        // Insertar todas las mallas
        foreach ($mallas as $mallaData) {
            MallaCurricular::firstOrCreate(
                ['codigo' => $mallaData['codigo']], // evita duplicados
                $mallaData
            );
        }

        $this->command->info('✅ Mallas curriculares creadas correctamente.');
    }
}

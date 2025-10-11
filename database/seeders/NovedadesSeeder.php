<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Novedad;
use App\Models\User;
use App\Models\Magister;
use Carbon\Carbon;

class NovedadesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('rol', 'administrador')->first();
        $magisters = Magister::all();

        if (!$admin) {
            $this->command->warn('⚠️ No se encontró un usuario administrador. Ejecuta UsersTableSeeder primero.');
            return;
        }

        $magisterSalud = $magisters->where('nombre', 'Gestión de Sistemas de Salud')->first();

        $novedades = [
            // ==========================================
            // NOVEDADES PROCESO DE ADMISIÓN 2025-2026
            // ==========================================
            [
                'titulo' => 'Inicio de Postulaciones - Magíster en Gestión de Sistemas de Salud',
                'contenido' => 'Se encuentran abiertas las postulaciones para el Magíster en Gestión de Sistemas de Salud, cohorte 2025-2026. Revisa los requisitos y documentación necesaria en nuestra página web. ¡No pierdas esta oportunidad!',
                'tipo_novedad' => 'admision',
                'visible_publico' => true,
                'magister_id' => $magisterSalud?->id,
                'es_urgente' => true,
                'color' => 'green',
                'icono' => '📝',
                'fecha_expiracion' => Carbon::create(2026, 7, 10), // Cierra el 10 de julio 2026
            ],
            [
                'titulo' => 'Término de Postulaciones - Magíster en Gestión de Sistemas de Salud',
                'contenido' => 'Recordatorio: Las postulaciones para el Magíster en Gestión de Sistemas de Salud cierran el 10 de julio de 2026. Asegúrate de completar todos los documentos requeridos antes de esta fecha.',
                'tipo_novedad' => 'admision',
                'visible_publico' => true,
                'magister_id' => $magisterSalud?->id,
                'es_urgente' => true,
                'color' => 'red',
                'icono' => '⏰',
                'fecha_expiracion' => Carbon::create(2026, 7, 10), // Cierra el 10 de julio 2026
            ],
            [
                'titulo' => 'Inicio de Clases - Magíster en Gestión de Sistemas de Salud',
                'contenido' => 'Las clases del Magíster en Gestión de Sistemas de Salud cohorte 2025-2026 dan inicio el 17 de julio de 2026. ¡Te esperamos para comenzar esta nueva etapa académica!',
                'tipo_novedad' => 'academica',
                'visible_publico' => true,
                'magister_id' => $magisterSalud?->id,
                'es_urgente' => false,
                'color' => 'blue',
                'icono' => '🎓',
                'fecha_expiracion' => Carbon::create(2026, 8, 31), // Expira al final del primer mes de clases
            ],

            // ==========================================
            // NOVEDADES EXPIRADAS (PROCESO 2024-2025)
            // ==========================================
            [
                'titulo' => 'Inicio de Postulaciones 2024-2025 (CERRADO)',
                'contenido' => 'Se abrieron las postulaciones para el Magíster en Gestión de Sistemas de Salud, cohorte 2024-2025. Este proceso ya finalizó.',
                'tipo_novedad' => 'admision',
                'visible_publico' => true,
                'magister_id' => $magisterSalud?->id,
                'es_urgente' => false,
                'color' => 'gray',
                'icono' => '📝',
                'fecha_expiracion' => Carbon::create(2024, 10, 1), // Ya expiró
            ],
            [
                'titulo' => 'Cierre de Postulaciones 2024-2025 (FINALIZADO)',
                'contenido' => 'Las postulaciones para la cohorte 2024-2025 del Magíster en Gestión de Sistemas de Salud cerraron el 12 de septiembre de 2025.',
                'tipo_novedad' => 'admision',
                'visible_publico' => true,
                'magister_id' => $magisterSalud?->id,
                'es_urgente' => false,
                'color' => 'gray',
                'icono' => '⏰',
                'fecha_expiracion' => Carbon::create(2025, 9, 12), // Ya expiró
            ],
            [
                'titulo' => 'Inicio de Clases 2024-2025 (FINALIZADO)',
                'contenido' => 'Las clases de la cohorte 2024-2025 iniciaron el 29 de septiembre de 2025.',
                'tipo_novedad' => 'academica',
                'visible_publico' => true,
                'magister_id' => $magisterSalud?->id,
                'es_urgente' => false,
                'color' => 'gray',
                'icono' => '🎓',
                'fecha_expiracion' => Carbon::create(2025, 9, 29), // Ya expiró
            ],
        ];

        $totalCreadas = 0;

        foreach ($novedades as $novedadData) {
            // Asignar user_id
            $novedadData['user_id'] = $admin->id;

            // Convertir roles_visibles a array si existe
            if (isset($novedadData['roles_visibles']) && is_array($novedadData['roles_visibles'])) {
                // Ya está como array
            } else {
                $novedadData['roles_visibles'] = null;
            }

            Novedad::create($novedadData);
            $totalCreadas++;
        }

        $this->command->info("✅ Se crearon $totalCreadas novedades del Magíster en Gestión de Sistemas de Salud.");
    }
}


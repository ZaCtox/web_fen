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

        $novedades = [
            // Novedades Públicas (visibles para todos)
            [
                'titulo' => 'Inicio del Año Académico 2025',
                'contenido' => 'La Facultad de Economía y Negocios da la bienvenida a todos los estudiantes de postgrado para el año académico 2025. Las clases del primer trimestre inician el 7 de marzo.',
                'tipo_novedad' => 'academica',
                'visible_publico' => true,
                'es_urgente' => false,
                'color' => 'blue',
                'icono' => '🎓',
                'fecha_expiracion' => Carbon::now()->addMonths(2),
            ],
            [
                'titulo' => 'Proceso de Admisión 2025 Abierto',
                'contenido' => 'Están abiertas las postulaciones para los programas de Magíster 2025. Revisa los requisitos y plazos en nuestra página de admisión. Cupos limitados.',
                'tipo_novedad' => 'admision',
                'visible_publico' => true,
                'es_urgente' => true,
                'color' => 'red',
                'icono' => '📝',
                'fecha_expiracion' => Carbon::now()->addMonth(),
            ],
            [
                'titulo' => 'Seminario Internacional de Economía Digital',
                'contenido' => 'Los invitamos al seminario internacional "Economía Digital y Transformación de Mercados" que se realizará el 15 de abril. Inscripciones abiertas para estudiantes y público general.',
                'tipo_novedad' => 'evento',
                'visible_publico' => true,
                'es_urgente' => false,
                'color' => 'green',
                'icono' => '🌐',
                'fecha_expiracion' => Carbon::now()->addDays(40),
            ],
            [
                'titulo' => 'Acreditación Internacional Renovada',
                'contenido' => 'Nos complace informar que nuestros programas de Magíster han renovado su acreditación internacional por 5 años más, destacando la calidad académica y pertinencia de nuestros programas.',
                'tipo_novedad' => 'institucional',
                'visible_publico' => true,
                'es_urgente' => false,
                'color' => 'yellow',
                'icono' => '🏆',
                'fecha_expiracion' => Carbon::now()->addMonths(6),
            ],
            [
                'titulo' => 'Nuevo Centro de Investigación en Políticas Públicas',
                'contenido' => 'La facultad inaugura su nuevo Centro de Investigación en Políticas Públicas, que realizará estudios aplicados sobre desarrollo económico regional.',
                'tipo_novedad' => 'institucional',
                'visible_publico' => true,
                'es_urgente' => false,
                'color' => 'purple',
                'icono' => '🔬',
                'fecha_expiracion' => null,
            ],

            // Novedades para Estudiantes
            [
                'titulo' => 'Calendario de Exámenes Primer Trimestre',
                'contenido' => 'Se ha publicado el calendario de exámenes del primer trimestre 2025. Revísalo en el portal del estudiante. Las fechas son del 25 de mayo al 5 de junio.',
                'tipo_novedad' => 'academica',
                'visible_publico' => false,
                'roles_visibles' => ['docente', 'asistente_postgrado', 'director_programa'],
                'es_urgente' => true,
                'color' => 'orange',
                'icono' => '📅',
                'fecha_expiracion' => Carbon::now()->addDays(60),
            ],
            [
                'titulo' => 'Plazo de Entrega de Trabajos de Grado',
                'contenido' => 'Recordatorio: El plazo para entregar los trabajos de grado del segundo año es el 30 de noviembre. Coordina con tu profesor guía.',
                'tipo_novedad' => 'academica',
                'visible_publico' => false,
                'roles_visibles' => ['docente', 'director_programa'],
                'es_urgente' => true,
                'color' => 'red',
                'icono' => '⏰',
                'fecha_expiracion' => Carbon::now()->addMonths(8),
            ],
            [
                'titulo' => 'Actualización de Biblioteca Digital',
                'contenido' => 'La biblioteca digital de la universidad ha actualizado su plataforma. Ahora cuenta con acceso a 15 nuevas bases de datos especializadas en economía y gestión.',
                'tipo_novedad' => 'servicio',
                'visible_publico' => false,
                'roles_visibles' => ['docente', 'asistente_postgrado'],
                'es_urgente' => false,
                'color' => 'blue',
                'icono' => '📚',
                'fecha_expiracion' => null,
            ],

            // Novedades para Personal Administrativo
            [
                'titulo' => 'Reunión de Coordinación Académica',
                'contenido' => 'Se convoca a reunión de coordinación académica el próximo viernes 12 a las 10:00 hrs en sala de reuniones. Asistencia obligatoria para directores y coordinadores.',
                'tipo_novedad' => 'administrativa',
                'visible_publico' => false,
                'roles_visibles' => ['director_programa', 'director_administrativo', 'asistente_programa'],
                'es_urgente' => true,
                'color' => 'red',
                'icono' => '👥',
                'fecha_expiracion' => Carbon::now()->addDays(5),
            ],
            [
                'titulo' => 'Nuevo Sistema de Gestión de Salas',
                'contenido' => 'A partir del lunes 10 de marzo, todas las reservas de salas deben realizarse a través del nuevo sistema Web FEN. Solicita tus credenciales con el área de informática.',
                'tipo_novedad' => 'sistema',
                'visible_publico' => false,
                'roles_visibles' => ['asistente_programa', 'asistente_postgrado', 'director_programa'],
                'es_urgente' => true,
                'color' => 'yellow',
                'icono' => '💻',
                'fecha_expiracion' => Carbon::now()->addDays(15),
            ],

            // Novedades por Magíster
            [
                'titulo' => 'Charla Magistral: Economía Circular',
                'contenido' => 'El programa de Economía invita a la charla magistral "Economía Circular y Desarrollo Sostenible" a cargo del Dr. Roberto Silva (U. de Concepción). Martes 20 de marzo, 18:00 hrs.',
                'tipo_novedad' => 'evento',
                'visible_publico' => true,
                'magister_id' => $magisters->where('nombre', 'Economía')->first()?->id,
                'es_urgente' => false,
                'color' => 'blue',
                'icono' => '♻️',
                'fecha_expiracion' => Carbon::now()->addDays(25),
            ],
            [
                'titulo' => 'Workshop: Nuevas Normativas Tributarias 2025',
                'contenido' => 'Taller práctico sobre las modificaciones al Código Tributario vigentes desde enero 2025. Dictado por expertos del SII. Sábado 15 de marzo, 9:00-13:00 hrs.',
                'tipo_novedad' => 'evento',
                'visible_publico' => true,
                'magister_id' => $magisters->where('nombre', 'Dirección y Planificación Tributaria')->first()?->id,
                'es_urgente' => true,
                'color' => 'red',
                'icono' => '💼',
                'fecha_expiracion' => Carbon::now()->addDays(10),
            ],
            [
                'titulo' => 'Pasantía en Hospital Regional',
                'contenido' => 'Abierta convocatoria para pasantía opcional en gestión hospitalaria en el Hospital Regional. Cupos limitados. Inscripciones hasta el 30 de marzo.',
                'tipo_novedad' => 'oportunidad',
                'visible_publico' => false,
                'magister_id' => $magisters->where('nombre', 'Gestión de Sistemas de Salud')->first()?->id,
                'roles_visibles' => ['docente'],
                'es_urgente' => true,
                'color' => 'green',
                'icono' => '🏥',
                'fecha_expiracion' => Carbon::now()->addDays(25),
            ],
            [
                'titulo' => 'Seminario: Reforma al Estado y Modernización',
                'contenido' => 'Seminario sobre procesos de reforma y modernización del Estado en América Latina. Participan destacados académicos y funcionarios públicos. Jueves 28 de marzo.',
                'tipo_novedad' => 'evento',
                'visible_publico' => true,
                'magister_id' => $magisters->where('nombre', 'Gestión y Políticas Públicas')->first()?->id,
                'es_urgente' => false,
                'color' => 'purple',
                'icono' => '🏛️',
                'fecha_expiracion' => Carbon::now()->addDays(23),
            ],

            // Novedades de Servicios
            [
                'titulo' => 'Horario de Atención Vacaciones de Invierno',
                'contenido' => 'Durante el período de vacaciones de invierno (15-26 de julio), la secretaría de postgrado atenderá en horario reducido: 9:00 a 14:00 hrs.',
                'tipo_novedad' => 'servicio',
                'visible_publico' => true,
                'es_urgente' => false,
                'color' => 'blue',
                'icono' => '🏢',
                'fecha_expiracion' => Carbon::now()->addMonths(4),
            ],
            [
                'titulo' => 'Mantención Sistema Eléctrico',
                'contenido' => 'Se informa que el día sábado 25 de marzo se realizará mantención del sistema eléctrico en el edificio FEN entre 8:00 y 14:00 hrs. No habrá clases programadas.',
                'tipo_novedad' => 'mantenimiento',
                'visible_publico' => true,
                'es_urgente' => true,
                'color' => 'orange',
                'icono' => '⚡',
                'fecha_expiracion' => Carbon::now()->addDays(20),
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

        $this->command->info("✅ Se crearon $totalCreadas novedades realistas para la facultad.");
    }
}


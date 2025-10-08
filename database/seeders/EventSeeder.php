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
        $magisters = Magister::all();
        $rooms = Room::all();
        $users = User::all();

        if ($magisters->isEmpty() || $rooms->isEmpty() || $users->isEmpty()) {
            $this->command->warn('⚠️ Faltan datos: asegúrate de tener magísteres, salas y usuarios cargados.');
            return;
        }

        $eventosAcademicos = [
            // Eventos académicos
            [
                'title' => 'Ceremonia de Inauguración Año Académico 2025',
                'description' => 'Ceremonia oficial de inicio del año académico con la presencia del Decano y autoridades universitarias. Se presenta el cronograma académico y las principales actividades del año.',
                'type' => 'evento',
                'duracion' => 3, // horas
            ],
            [
                'title' => 'Seminario: Tendencias en Economía Digital',
                'description' => 'Seminario académico sobre las últimas tendencias en economía digital y transformación de los mercados financieros. Expositor invitado: Dr. Carlos Mena, Universidad de Chile.',
                'type' => 'evento',
                'duracion' => 2,
            ],
            [
                'title' => 'Workshop: Metodologías de Investigación Cuantitativa',
                'description' => 'Taller práctico sobre metodologías cuantitativas aplicadas a la investigación económica. Incluye sesiones prácticas con software estadístico.',
                'type' => 'actividad',
                'duracion' => 4,
            ],
            [
                'title' => 'Charla: Políticas Públicas Post-Pandemia',
                'description' => 'Charla magistral sobre el diseño e implementación de políticas públicas en el contexto post-pandemia en Chile.',
                'type' => 'evento',
                'duracion' => 2,
            ],
            [
                'title' => 'Presentación de Proyectos de Tesis',
                'description' => 'Presentación pública de avances de proyectos de tesis de estudiantes de segundo año. Comisión evaluadora conformada por docentes del programa.',
                'type' => 'actividad',
                'duracion' => 5,
            ],
            
            // Reuniones administrativas
            [
                'title' => 'Reunión Comité Académico',
                'description' => 'Reunión mensual del comité académico para revisar el avance del programa, evaluar propuestas de mejora y analizar indicadores de calidad.',
                'type' => 'reunión',
                'duracion' => 2,
            ],
            [
                'title' => 'Consejo de Profesores',
                'description' => 'Consejo trimestral de profesores para coordinar contenidos, evaluar metodologías docentes y planificar actividades académicas.',
                'type' => 'reunión',
                'duracion' => 3,
            ],
            [
                'title' => 'Reunión de Coordinación Postgrado',
                'description' => 'Reunión de coordinación entre directores de programas de postgrado y equipo administrativo para alinear procesos y resolver temas pendientes.',
                'type' => 'reunión',
                'duracion' => 2,
            ],
            
            // Actividades de extensión
            [
                'title' => 'Conferencia: Innovación en Gestión Tributaria',
                'description' => 'Conferencia sobre innovación y digitalización en la gestión tributaria chilena. Participan representantes del SII y empresas consultoras.',
                'type' => 'evento',
                'duracion' => 3,
            ],
            [
                'title' => 'Mesa Redonda: Desafíos de la Gestión en Salud',
                'description' => 'Mesa redonda con directores de hospitales y centros de salud de la región para discutir los principales desafíos del sector.',
                'type' => 'evento',
                'duracion' => 2,
            ],
            [
                'title' => 'Seminario de Egresados',
                'description' => 'Encuentro anual de egresados del programa donde se comparten experiencias profesionales y se fortalece la red de contactos.',
                'type' => 'evento',
                'duracion' => 4,
            ],
            
            // Actividades de evaluación
            [
                'title' => 'Exámenes de Grado - Primera Convocatoria',
                'description' => 'Primera convocatoria de exámenes de grado del semestre. Comisiones evaluadoras conformadas según reglamento vigente.',
                'type' => 'actividad',
                'duracion' => 6,
            ],
            [
                'title' => 'Defensa de Tesis Doctoral',
                'description' => 'Defensa pública de tesis doctoral. Comisión evaluadora integrada por especialistas nacionales e internacionales.',
                'type' => 'actividad',
                'duracion' => 3,
            ],
            
            // Otros eventos
            [
                'title' => 'Inducción Estudiantes Nuevos',
                'description' => 'Jornada de inducción para estudiantes de nuevo ingreso. Se presenta la estructura del programa, reglamentos, servicios disponibles y equipo docente.',
                'type' => 'actividad',
                'duracion' => 4,
            ],
            [
                'title' => 'Taller de Habilidades Blandas',
                'description' => 'Taller sobre desarrollo de habilidades blandas para profesionales: comunicación efectiva, liderazgo y trabajo en equipo.',
                'type' => 'actividad',
                'duracion' => 3,
            ],
        ];

        $eventosCreados = 0;
        $baseDate = Carbon::now();

        // Generar eventos para los próximos 90 días
        foreach (range(1, 25) as $i) {
            $evento = $eventosAcademicos[array_rand($eventosAcademicos)];
            
            // Generar fecha aleatoria en los próximos 90 días
            $diasAdelante = rand(1, 90);
            $horaInicio = rand(8, 17); // Entre 8 AM y 5 PM
            
            $inicio = (clone $baseDate)->addDays($diasAdelante)->setTime($horaInicio, 0, 0);
            $fin = (clone $inicio)->addHours($evento['duracion']);

            // Determinar estado según la fecha
            $estado = 'activo';
            if ($diasAdelante > 60 && rand(1, 10) > 8) {
                $estado = 'cancelado'; // 20% de probabilidad de cancelación para eventos lejanos
            } elseif ($diasAdelante < 0) {
                $estado = 'finalizado';
            }

            Event::create([
                'title' => $evento['title'],
                'description' => $evento['description'],
                'magister_id' => rand(1, 10) > 3 ? $magisters->random()->id : null, // 70% asociado a un magíster
                'room_id' => $rooms->random()->id,
                'start_time' => $inicio,
                'end_time' => $fin,
                'created_by' => $users->random()->id,
                'type' => $evento['type'],
                'status' => $estado,
            ]);

            $eventosCreados++;
        }

        // Agregar algunos eventos pasados (últimos 60 días)
        foreach (range(1, 15) as $i) {
            $evento = $eventosAcademicos[array_rand($eventosAcademicos)];
            
            $diasAtras = rand(1, 60);
            $horaInicio = rand(8, 17);
            
            $inicio = (clone $baseDate)->subDays($diasAtras)->setTime($horaInicio, 0, 0);
            $fin = (clone $inicio)->addHours($evento['duracion']);

            Event::create([
                'title' => $evento['title'],
                'description' => $evento['description'],
                'magister_id' => rand(1, 10) > 3 ? $magisters->random()->id : null,
                'room_id' => $rooms->random()->id,
                'start_time' => $inicio,
                'end_time' => $fin,
                'created_by' => $users->random()->id,
                'type' => $evento['type'],
                'status' => 'finalizado',
            ]);

            $eventosCreados++;
        }

        $this->command->info("✅ Se crearon $eventosCreados eventos académicos realistas.");
    }
}

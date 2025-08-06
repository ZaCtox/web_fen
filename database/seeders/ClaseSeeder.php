<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Room;
use App\Models\Period;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Clase;

class ClaseSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = Period::pluck('id')->toArray();
        $salas = Room::pluck('id')->toArray();

        $bloques = [
            'Viernes' => [
                ['15:00:00', '16:30:00'],
                ['17:00:00', '18:30:00'],
            ],
            'Sábado' => [
                ['08:30:00', '10:00:00'],
                ['10:15:00', '11:45:00'],
                ['12:00:00', '13:30:00'],
            ]
        ];

        $modalidades = ['presencial', 'online', 'hibrida'];
        $total = 0;

        foreach (Magister::with('courses.period')->get() as $magister) {
            $cursos = $magister->courses->filter(fn($c) => $c->period_id !== null);

            foreach ($cursos as $curso) {
                foreach (range(1, 2) as $i) {
                    $dia = array_rand($bloques);
                    $horario = $bloques[$dia][array_rand($bloques[$dia])];
                    $modality = $modalidades[array_rand($modalidades)];

                    Clase::create([
                        'course_id' => $curso->id,
                        'period_id' => $curso->period_id,
                        'room_id' => $modality === 'online' ? null : $salas[array_rand($salas)],
                        'modality' => $modality,
                        'dia' => $dia,
                        'hora_inicio' => $horario[0],
                        'hora_fin' => $horario[1],
                        'url_zoom' => $modality !== 'presencial' ? 'https://us02web.zoom.us/fake' . rand(1, 100) : null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $total++;
                }
            }
        }

        $this->command->info("✅ Se generaron $total clases en la tabla 'clases'.");
    }
}

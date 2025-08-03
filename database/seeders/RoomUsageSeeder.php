<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Room;
use App\Models\Period;
use App\Models\Course;
use App\Models\Magister;

class RoomUsageSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = Period::whereIn('anio', [1])->pluck('id')->toArray();
        $salas = Room::where('name', '!=', 'Sala Reuniones')->pluck('id')->toArray();

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

        $totalAsignaciones = 0;

        foreach (Magister::with('courses')->get() as $magister) {
            $cursos = $magister->courses->pluck('id')->toArray();

            if (empty($cursos)) continue;

            foreach (range(1, 4) as $i) {
                $dia = array_rand($bloques); // "Viernes" o "Sábado"
                $horario = $bloques[$dia][array_rand($bloques[$dia])];

                DB::table('room_usages')->insert([
                    'period_id' => $periodos[array_rand($periodos)],
                    'course_id' => $cursos[array_rand($cursos)],
                    'room_id' => $salas[array_rand($salas)],
                    'dia' => $dia,
                    'hora_inicio' => $horario[0],
                    'hora_fin' => $horario[1],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $totalAsignaciones++;
            }
        }

        $this->command->info("✅ Se generaron $totalAsignaciones asignaciones académicas en room_usages.");
    }
}

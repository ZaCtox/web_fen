<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/cursos.json');

        if (!file_exists($jsonPath)) {
            $this->command->error("Archivo cursos.json no encontrado en: $jsonPath");
            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        foreach ($data as $programa => $asignaturas) {
            foreach ($asignaturas as $nombre) {
                Course::create([
                    'nombre' => $nombre,
                    'programa' => $programa
                ]);
            }
        }

        $this->command->info("Cursos importados correctamente desde cursos.json");
    }
}

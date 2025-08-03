<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Period;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Estructura completa con año, trimestre y cursos
        $data = [
            'Economía' => [
                [1, 1, [
                    "Matemáticas Avanzadas para Economía",
                    "Microeconomía Avanzada I",
                    "Macroeconomía Avanzada I",
                ]],
                [1, 2, [
                    "Econometría I",
                    "Microeconomía Avanzada II",
                    "Macroeconomía Avanzada II",
                ]],
                [1, 3, [
                    "Econometría II",
                    "Microeconomía Avanzada III",
                    "Metodología de Investigación en Economía",
                ]],
                [2, 1, ["Electivo I", "Electivo II", "Proyecto de Tesis"]],
                [2, 2, ["Tesis"]],
                [2, 3, ["Tesis"]],
            ],
            // Agrega aquí los otros magísteres igual que el formato anterior...
        ];

        foreach ($data as $magisterNombre => $periodos) {
            $magister = Magister::where('nombre', "Magíster en $magisterNombre")->first();

            if (!$magister) {
                throw new \Exception("Magíster no encontrado: 'Magíster en $magisterNombre'");
            }

            foreach ($periodos as [$anio, $trimestre, $cursos]) {
                $period = Period::where('anio', $anio)->where('numero', $trimestre)->first();

                if (!$period) {
                    throw new \Exception("Periodo no encontrado para año $anio, trimestre $trimestre");
                }

                foreach ($cursos as $curso) {
                    Course::create([
                        'nombre' => $curso,
                        'magister_id' => $magister->id,
                        'period_id' => $period->id,
                    ]);
                }
            }
        }
    }
}

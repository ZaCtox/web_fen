<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Personal Directivo
            [
                'nombre' => 'José Leonardo Castillo',
                'cargo' => 'Director Administrativo',
                'telefono' => '+56 712417313',
                'anexo' => '7313',
                'email' => 'josecastillo@utalca.cl'
            ],
            [
                'nombre' => 'Carolina Cáceres',
                'cargo' => 'Asistente Decanato',
                'telefono' => '+56 712200310',
                'anexo' => '0310',
                'email' => 'carolina.caceres@utalca.cl'
            ],

            // Comunicaciones y Diseño
            [
                'nombre' => 'Wendy Lucena Barboza',
                'cargo' => 'Periodista FEN',
                'telefono' => '+56 712200381',
                'anexo' => '0381',
                'email' => 'wendy.lucena@utalca.cl'
            ],
            [
                'nombre' => 'Daniel Castro Salas',
                'cargo' => 'Diseñador FEN',
                'telefono' => '+56 9 87654321',
                'anexo' => null,
                'email' => 'daniel.castro@utalca.cl'
            ],

            // Acreditación y Calidad
            [
                'nombre' => 'Daniela Ortega Muñoz',
                'cargo' => 'Profesional de Acreditación y Calidad',
                'telefono' => '+56 712417377',
                'anexo' => '3177',
                'email' => 'dortega@utalca.cl'
            ],

            // Asistentes de Programa - Ingeniería Comercial
            [
                'nombre' => 'Carolina Balmaceda Rojas',
                'cargo' => 'Asistente de Ingeniería Comercial, Campus Talca',
                'telefono' => '+56 712200456',
                'anexo' => '0456',
                'email' => 'cbalmaceda@utalca.cl'
            ],
            [
                'nombre' => 'Carmen Carreño Flores',
                'cargo' => 'Asistente de Ingeniería Comercial, Campus Talca',
                'telefono' => '+56 712202457',
                'anexo' => '2457',
                'email' => 'ccarreno@utalca.cl'
            ],

            // Asistentes de Escuelas
            [
                'nombre' => 'Mirta Rojas',
                'cargo' => 'Asistente Escuela Auditoría e Ingeniería en Control de Gestión',
                'telefono' => '+56 712200316',
                'anexo' => '0316',
                'email' => 'mirtarojas@utalca.cl'
            ],
            [
                'nombre' => 'Carolina Quitral',
                'cargo' => 'Asistente Escuela Ingeniería Informática Empresarial',
                'telefono' => '+56 712200315',
                'anexo' => '0315',
                'email' => 'cquitral@utalca.cl'
            ],
            [
                'nombre' => 'Tamara Aravena',
                'cargo' => 'Asistente Carrera Contador Público y Auditor',
                'telefono' => '+56 712201580',
                'anexo' => '1580',
                'email' => 'tamara.aravena@utalca.cl'
            ],

            // Oficina de Postgrado
            [
                'nombre' => 'Patricia Muñoz',
                'cargo' => 'Jefa Oficina de Postgrado',
                'telefono' => '+56 712201514',
                'anexo' => '1514',
                'email' => 'pamunoz@utalca.cl'
            ],
            [
                'nombre' => 'July Basoalto',
                'cargo' => 'Asistente Oficina de Postgrados FEN',
                'telefono' => '+56 712200312',
                'anexo' => '0312',
                'email' => 'jbasoalto@utalca.cl'
            ],
            [
                'nombre' => 'Ivonne Henríquez Toro',
                'cargo' => 'Coordinadora Oficina de Postgrados FEN',
                'telefono' => '+56 712200321',
                'anexo' => '0321',
                'email' => 'ivonne.henriquez@utalca.cl'
            ],
            [
                'nombre' => 'Camila González',
                'cargo' => 'Coordinadora Oficina de Postgrados FEN',
                'telefono' => '+56 9 98765432',
                'anexo' => '0322',
                'email' => 'camila.gonzalezd@utalca.cl'
            ],

            // Centro de Desarrollo Empresarial
            [
                'nombre' => 'Margarita González',
                'cargo' => 'Asistente Centro de Desarrollo Empresarial',
                'telefono' => '+56 712200386',
                'anexo' => '0386',
                'email' => 'mgonzale@utalca.cl'
            ],

            // Personal de Apoyo
            [
                'nombre' => 'Cristian Barrientos',
                'cargo' => 'Auxiliar de Facultad, Campus Talca',
                'telefono' => '+56 9 76543210',
                'anexo' => null,
                'email' => 'cristian.barrientos@utalca.cl'
            ],
            [
                'nombre' => 'Miguel Suárez',
                'cargo' => 'Informático FEN, Campus Talca',
                'telefono' => '+56 712201789',
                'anexo' => '1789',
                'email' => 'msuarez@utalca.cl'
            ],
            [
                'nombre' => 'Juan Azares',
                'cargo' => 'Encargado de Laboratorio Escuela Ingeniería Informática',
                'telefono' => '+56 9 65432109',
                'anexo' => '0420',
                'email' => 'jazares@utalca.cl'
            ],
        ];

        foreach ($data as $staff) {
            Staff::firstOrCreate(
                ['email' => $staff['email']],
                [
                    'nombre' => $staff['nombre'],
                    'cargo' => $staff['cargo'],
                    'telefono' => $staff['telefono'],
                    'anexo' => $staff['anexo']
                ]
            );
        }

        $this->command->info('✅ Personal de FEN creado correctamente con teléfonos y anexos válidos.');
    }
}

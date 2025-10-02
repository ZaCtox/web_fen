<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nombre' => 'José Leonardo Castillo', 'cargo' => 'Director Administrativo', 'telefono' => '+56 71 241 7313', 'email' => 'josecastillo@utalca.cl'],
            ['nombre' => 'Carolina Cáceres', 'cargo' => 'Asistente Decanato', 'telefono' => '+56 71 220 0310', 'email' => 'carolina.caceres@utalca.cl'],
            ['nombre' => 'Wendy Lucena Barboza', 'cargo' => 'Periodista FEN', 'telefono' => '+56 71 220 0381', 'email' => 'wendy.lucena@utalca.cl'],
            ['nombre' => 'Daniel Castro Salas', 'cargo' => 'Diseñador FEN', 'telefono' => '+56 71 220 0381', 'email' => 'daniel.castro@utalca.cl'],
            ['nombre' => 'Daniela Ortega Muñoz', 'cargo' => 'Profesional de Acreditación y Calidad', 'telefono' => '(+56-71) 2417377 (Anexo 3177)', 'email' => 'dortega@utalca.cl'],
            ['nombre' => 'Carolina Balmaceda Rojas', 'cargo' => 'Asistente de Ingeniería Comercial, Campus Talca', 'telefono' => '+56 71-2200456', 'email' => 'cbalmaceda@utalca.cl'],
            ['nombre' => 'Carmen Carreño Flores', 'cargo' => 'Asistente de Ingeniería Comercial, Campus Talca', 'telefono' => '+56 71 220 2457', 'email' => 'ccarreno@utalca.cl'],
            ['nombre' => 'Mirta Rojas', 'cargo' => 'Asistente Escuela Auditoría e Ingeniería en Control de Gestión, Campus Talca', 'telefono' => '+56 71 220 0316', 'email' => 'mirtarojas@utalca.cl'],
            ['nombre' => 'Carolina Quitral', 'cargo' => 'Asistente Escuela Ingeniería Informática Empresarial, Campus Talca', 'telefono' => '+56 71 220 0315', 'email' => 'cquitral@utalca.cl'],
            ['nombre' => 'Tamara Aravena', 'cargo' => 'Asistente Carrera Contador Público y Auditor', 'telefono' => '+56 71 2201580', 'email' => 'tamara.aravena@utalca.cl'],
            ['nombre' => 'Patricia Muñoz', 'cargo' => 'Jefa Oficina de Postgrado', 'telefono' => '+56 71 220 1514', 'email' => 'pamunoz@utalca.cl'],
            ['nombre' => 'July Basoalto', 'cargo' => 'Asistente Oficina de Postgrados FEN', 'telefono' => '+56 71 220 0312', 'email' => 'jbasoalto@utalca.cl'],
            ['nombre' => 'Ivonne Henríquez Toro', 'cargo' => 'Coordinadora Oficina de Postgrados FEN', 'telefono' => '+56 71 220 0321', 'email' => 'ivonne.henriquez@utalca.cl'],
            ['nombre' => 'Camila González', 'cargo' => 'Coordinadora Oficina de Postgrados FEN', 'telefono' => '+56 71 220 0321', 'email' => 'camila.gonzalezd@utalca.cl'],
            ['nombre' => 'Margarita González', 'cargo' => 'Asistente Centro de Desarrollo Empresarial', 'telefono' => '+56 71 220 0386', 'email' => 'mgonzale@utalca.cl'],
            ['nombre' => 'Cristian Barrientos', 'cargo' => 'Auxiliar de Facultad, Campus Talca', 'telefono' => '+56 71 220 0310', 'email' => 'cristian.barrientos@utalca.cl'],
            ['nombre' => 'Miguel Suárez', 'cargo' => 'Informático FEN, Campus Talca', 'telefono' => '+56 71 220 1789', 'email' => 'msuarez@utalca.cl'],
            ['nombre' => 'Juan Azares', 'cargo' => 'Encargado de Laboratorio Escuela Ingeniería Informática Empresarial', 'telefono' => null, 'email' => 'jazares@utalca.cl'],
        ];

        foreach ($data as $staff) {
            Staff::firstOrCreate(
                ['email' => $staff['email']],
                [
                    'nombre' => $staff['nombre'],
                    'cargo' => $staff['cargo'],
                    'telefono' => $staff['telefono'] ?? null,
                    'anexo' => $staff['anexo'] ?? null
                ]
            );
        }
    }
}

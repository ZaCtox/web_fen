<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Magister;

class MagistersTableSeeder extends Seeder
{
    public function run()
    {
        $magisters = [
            [
                'nombre' => 'Gestión de Sistemas de Salud',
                'color' => '#10b981',
                'orden' => 1,
                'encargado' => 'Luis Canales',
                'asistente' => 'Mary Isabel Sepúlveda G.',
                'telefono' => '+56 712200313',
                'anexo' => '0313',
                'correo' => 'msepulveda@utalca.cl'
            ],
            [
                'nombre' => 'Economía',
                'color' => '#3b82f6',
                'orden' => 2,
                'encargado' => 'Pablo Neudörfer, PhD',
                'asistente' => 'July Basoalto Riveros',
                'telefono' => '+56 712200312',
                'anexo' => '0312',
                'correo' => 'jbasoalto@utalca.cl'
            ],
            [
                'nombre' => 'Dirección y Planificación Tributaria',
                'color' => '#ef4444',
                'orden' => 3,
                'encargado' => 'Verónica Mies Moreno',
                'asistente' => 'Ivonne Henríquez Toro',
                'telefono' => '+56 71 220 0321',
                'anexo' => '3321',
                'correo' => 'ihenriquez@utalca.cl'
            ],
            [
                'nombre' => 'Gestión y Políticas Públicas',
                'color' => '#f97316',
                'orden' => 4,
                'encargado' => 'Dr. Jorge Navarrete',
                'asistente' => 'Mary Isabel Sepúlveda G.',
                'telefono' => '+56 712200313',
                'anexo' => '0313',
                'correo' => 'msepulveda@utalca.cl'
            ]
        ];

        foreach ($magisters as $data) {
            Magister::firstOrCreate(
                ['nombre' => $data['nombre']], // evita duplicados por nombre
                $data
            );
        }

        $this->command->info('✅ Magísteres creados correctamente con encargados reales de FEN.');
    }
}

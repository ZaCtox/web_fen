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
                'nombre' => 'Economía',
                'color' => '#3b82f6',
                'encargado' => 'Dr. Juan Pérez',
                'telefono' => '712345678',
                'correo' => 'jperez@utalca.cl'
            ],
            [
                'nombre' => 'Dirección y Planificación Tributaria',
                'color' => '#ef4444',
                'encargado' => 'Dra. María López',
                'telefono' => '712345679',
                'correo' => 'mlopez@utalca.cl'
            ],
            [
                'nombre' => 'Gestión de Sistemas de Salud',
                'color' => '#10b981',
                'encargado' => 'Dr. Carlos Díaz',
                'telefono' => '712345680',
                'correo' => 'cdiaz@utalca.cl'
            ],
            [
                'nombre' => 'Gestión y Políticas Públicas',
                'color' => '#f97316',
                'encargado' => 'Dra. Ana Ríos',
                'telefono' => '712345681',
                'correo' => 'arios@utalca.cl'
            ]
        ];

        foreach ($magisters as $data) {
            Magister::create($data);
        }

        $this->command->info('✅ Magísteres creados correctamente con colores y encargados.');
    }

}

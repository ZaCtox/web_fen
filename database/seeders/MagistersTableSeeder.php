<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Magister;

class MagistersTableSeeder extends Seeder
{
    public function run()
    {
        $nombres = [
            'Economía',
            'Dirección y Planificación Tributaria',
            'Gestión de Sistemas de Salud',
            'Gestión y Políticas Públicas'
        ];

        foreach ($nombres as $nombre) {
            Magister::create([
                'nombre' => $nombre
            ]);
        }
    }
}

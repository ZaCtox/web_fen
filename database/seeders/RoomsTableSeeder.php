<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomsTableSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            [
                'name' => 'Sala FEN 1',
                'location' => 'Edificio FEN, Piso 1',
                'capacity' => 35,
                'description' => 'Sala con proyector, aire acondicionado y pizarra digital',
            ],
            [
                'name' => 'Sala FEN 2',
                'location' => 'Edificio FEN, Piso 1',
                'capacity' => 30,
                'description' => 'Sala estándar para clases de postgrado',
            ],
            [
                'name' => 'Sala Computación',
                'location' => 'Edificio FEN, Piso 1',
                'capacity' => 25,
                'description' => 'Sala equipada con computadores y red cableada',
            ],
            [
                'name' => 'Auditorio FEN',
                'location' => 'Edificio FEN',
                'capacity' => 12,
                'description' => 'Sala para reuniones académicas y administrativas',
            ],
            [
                'name' => 'Arrayan 1',
                'location' => 'Laboratorio de Ingeniería Informática Empresarial',
                'capacity' => 12,
                'description' => 'Sala para trabajos en computacion',
            ],
            [
                'name' => 'Arrayan 2',
                'location' => 'Laboratorio de Ingeniería Informática Empresarial',
                'capacity' => 30,
                'description' => 'Sala para trabajos en computacion e impresión',
            ],
            [
                'name' => 'Arrayan 3',
                'location' => 'Laboratorio de Ingeniería Informática Empresarial',
                'capacity' => 22,
                'description' => 'Sala para clases híbridas',
            ]
        ];

        foreach ($rooms as $room) {
            Room::create(array_merge($room, [
                'calefaccion' => fake()->boolean(),
                'energia_electrica' => fake()->boolean(),
                'existe_aseo' => fake()->boolean(),
                'plumones' => fake()->boolean(),
                'borrador' => fake()->boolean(),
                'pizarra_limpia' => fake()->boolean(),
                'computador_funcional' => fake()->boolean(),
                'cables_computador' => fake()->boolean(),
                'control_remoto_camara' => fake()->boolean(),
                'televisor_funcional' => fake()->boolean(),
            ]));
        }

        $this->command->info("✅ Salas creadas con atributos aleatorios de mantención.");
    }
}

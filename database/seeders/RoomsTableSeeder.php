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
                'location' => 'Edificio Central, Piso 1',
                'capacity' => 35,
                'description' => 'Sala con proyector y pizarra digital',
            ],
            [
                'name' => 'Sala FEN 2',
                'location' => 'Edificio Central, Piso 2',
                'capacity' => 30,
                'description' => 'Sala estándar para clases de postgrado',
            ],
            [
                'name' => 'Sala Computación',
                'location' => 'Edificio Norte, Piso 1',
                'capacity' => 25,
                'description' => 'Sala equipada con computadores y red cableada',
            ],
            [
                'name' => 'Sala Reuniones',
                'location' => 'Edificio Dirección FEN',
                'capacity' => 12,
                'description' => 'Sala para reuniones académicas y administrativas',
            ]
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}

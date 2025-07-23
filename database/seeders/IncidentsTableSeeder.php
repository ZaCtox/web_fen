<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;
use App\Models\Room;
use Illuminate\Support\Str;

class IncidentsTableSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'admin@webfen.cl')->first();
        $rooms = Room::all();

        if (!$admin || $rooms->isEmpty()) {
            $this->command->warn('No se pudo insertar incidencias: asegúrate de tener al menos 1 usuario admin y salas.');
            return;
        }

        $titulos = [
            'Luz quemada',
            'Proyector no enciende',
            'Silla rota',
            'Problemas con el aire acondicionado',
            'Pantalla desconectada',
            'Manchas en la pizarra',
        ];

        foreach ($titulos as $titulo) {
            Incident::create([
                'titulo' => $titulo,
                'descripcion' => 'Descripción generada automáticamente para: ' . $titulo,
                'estado' => fake()->randomElement(['pendiente', 'resuelta']),
                'resuelta_en' => null,
                'user_id' => $admin->id,
                'room_id' => $rooms->random()->id,
                'imagen' => null,
                'public_id' => null,
            ]);
        }
    }
}

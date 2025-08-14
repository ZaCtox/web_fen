<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Magister;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $magisters = Magister::pluck('id')->toArray();
        $rooms = Room::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();

        $tipos = ['evento', 'reunión', 'actividad', 'otro'];
        $estados = ['activo', 'cancelado', 'finalizado'];

        $eventosCreados = 0;

        foreach (range(1, 10) as $i) {
            $inicio = Carbon::now()->addDays(rand(1, 30))->setTime(rand(8, 18), 0);
            $fin = (clone $inicio)->addHour();

            Event::create([
                'title' => 'Evento de prueba ' . $i,
                'description' => 'Descripción para evento ' . $i,
                'magister_id' => $magisters[array_rand($magisters)],
                'room_id' => $rooms[array_rand($rooms)],
                'start_time' => $inicio,
                'end_time' => $fin,
                'created_by' => $users[array_rand($users)],
                'type' => $tipos[array_rand($tipos)],
                'status' => $estados[array_rand($estados)],
            ]);

            $eventosCreados++;
        }

        $this->command->info("✅ Se crearon $eventosCreados eventos de prueba.");
    }
}

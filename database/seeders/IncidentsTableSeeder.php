<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;
use App\Models\Room;
use App\Models\Period;
use Illuminate\Support\Carbon;

class IncidentsTableSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'admin@webfen.cl')->first();
        $rooms = Room::all();
        $periods = Period::all();

        if (!$admin || $rooms->isEmpty() || $periods->isEmpty()) {
            $this->command->warn('Faltan datos: asegúrate de tener usuario admin, salas y períodos cargados.');
            return;
        }

        $titulos = [
            'Luz quemada',
            'Proyector no enciende',
            'Silla rota',
            'Problemas con el aire acondicionado',
            'Pantalla desconectada',
            'Manchas en la pizarra',
            'Ventana rota',
            'Cortina caída',
            'Enchufe suelto',
            'Ruido molesto desde el pasillo',
        ];

        // Años que quieres simular desde 2026 hacia atrás
        $anios = [2026, 2025, 2024, 2023];

        foreach ($anios as $anioSimulado) {
            foreach ($periods as $periodoBase) {
                // Crear nuevas fechas ajustadas al año deseado
                $fechaInicio = Carbon::parse($periodoBase->fecha_inicio)->year($anioSimulado);
                $fechaFin = Carbon::parse($periodoBase->fecha_fin)->year($anioSimulado);

                // Asegurar que no se invierta el rango por cambios de año
                if ($fechaFin->lessThan($fechaInicio)) {
                    $fechaFin->addYear();
                }

                // Crear 5 incidencias para este período simulado
                for ($i = 0; $i < 5; $i++) {
                    $titulo = fake()->randomElement($titulos);
                    $fecha = fake()->dateTimeBetween($fechaInicio, $fechaFin);

                    Incident::create([
                        'titulo' => $titulo,
                        'descripcion' => 'Generada automáticamente para: ' . $titulo,
                        'estado' => fake()->randomElement(['pendiente', 'resuelta']),
                        'created_at' => $fecha,
                        'updated_at' => $fecha,
                        'resuelta_en' => null,
                        'user_id' => $admin->id,
                        'room_id' => $rooms->random()->id,
                        'imagen' => null,
                        'public_id' => null,
                    ]);
                }
            }
        }

        $this->command->info('✅ Incidencias generadas correctamente para años 2023–2026.');
    }
}

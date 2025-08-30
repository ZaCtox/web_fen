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

        $anios = [2026, 2025, 2024, 2023];

        foreach ($anios as $anioSimulado) {
            foreach ($periods as $periodoBase) {
                $fechaInicio = Carbon::parse($periodoBase->fecha_inicio)->year($anioSimulado);
                $fechaFin = Carbon::parse($periodoBase->fecha_fin)->year($anioSimulado);

                if ($fechaFin->lessThan($fechaInicio)) {
                    $fechaFin->addYear();
                }

                for ($i = 0; $i < 5; $i++) {
                    $titulo = fake()->randomElement($titulos);
                    $fecha = fake()->dateTimeBetween($fechaInicio, $fechaFin);

                    $estado = fake()->randomElement(['pendiente', 'resuelta', 'en_revision', 'no_resuelta']);

                    $comentario = null;
                    if ($estado === 'no_resuelta') {
                        $comentario = 'Se revisó el problema pero no fue posible resolverlo completamente.';
                    } elseif ($estado === 'en_revision') {
                        $comentario = 'El incidente está siendo evaluado por el equipo de soporte técnico.';
                    }

                    Incident::create([
                        'titulo' => $titulo,
                        'descripcion' => 'Generada automáticamente para: ' . $titulo,
                        'estado' => $estado,
                        'comentario' => $comentario,
                        'created_at' => $fecha,
                        'updated_at' => $fecha,
                        'resuelta_en' => $estado === 'resuelta' ? Carbon::parse($fecha)->addDays(rand(1, 10)) : null,
                        'user_id' => $admin->id,
                        'room_id' => $rooms->random()->id,
                        'imagen' => null,
                        'public_id' => null,
                    ]);
                }
            }
        }

        $this->command->info('✅ Incidencias generadas correctamente con comentarios cuando corresponde (2023–2026).');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Room;
use App\Models\Period;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Clase;
use App\Models\ClaseSesion;
use App\Models\User;

class ClaseSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener datos necesarios
        $magisterSalud = Magister::where('nombre', 'Gestión de Sistemas de Salud')->first();
        $periodo = Period::where('cohorte', '2025-2026')->where('anio', 1)->where('numero', 1)->first();
        $salaFEN1 = Room::where('name', 'Sala FEN 1')->first();

        if (!$magisterSalud || !$periodo || !$salaFEN1) {
            $this->command->warn('⚠️ Faltan datos necesarios (Magíster, Período o Sala).');
            return;
        }

        // Crear usuarios docentes
        $docentes = [
            'Margarita Pereira' => User::firstOrCreate(
                ['email' => 'mpereira@utalca.cl'],
                ['name' => 'Margarita Pereira', 'password' => bcrypt('docente123'), 'rol' => 'docente']
            ),
            'Andrés Riquelme' => User::firstOrCreate(
                ['email' => 'ariquelme@utalca.cl'],
                ['name' => 'Andrés Riquelme', 'password' => bcrypt('docente123'), 'rol' => 'docente']
            ),
            'Milton Inostroza' => User::firstOrCreate(
                ['email' => 'minostroza@utalca.cl'],
                ['name' => 'Milton Inostroza', 'password' => bcrypt('docente123'), 'rol' => 'docente']
            ),
            'Sandra Alvear' => User::firstOrCreate(
                ['email' => 'salvear@utalca.cl'],
                ['name' => 'Sandra Alvear', 'password' => bcrypt('docente123'), 'rol' => 'docente']
            ),
        ];

        // URL de Zoom compartida
        $zoomUrl = 'https://reuna.zoom.us/j/82980173545';
        $zoomInfo = 'ID: 829 8017 3545 | Código: 712683';

        $modulos = [
            // MÓDULO 1: HABILIDADES DE APRENDIZAJE
            [
                'nombre' => 'Taller 1 – Habilidades de Aprendizaje: Presentación Efectiva, Trabajo en Equipo, Metodología de Casos',
                'responsable' => 'Margarita Pereira',
                'sesiones' => [
                    ['fecha' => '2025-10-03', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-10-04', 'dia' => 'Sábado', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-10-10', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-10-11', 'dia' => 'Sábado', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                ],
            ],
            // MÓDULO 2: ECONOMÍA
            [
                'nombre' => 'Economía',
                'responsable' => 'Andrés Riquelme',
                'sesiones' => [
                    ['fecha' => '2025-10-17', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-10-18', 'dia' => 'Sábado', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-10-24', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-10-25', 'dia' => 'Sábado', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-11-07', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                ],
            ],
            // MÓDULO 3: ADMINISTRACIÓN
            [
                'nombre' => 'Administración',
                'responsable' => 'Milton Inostroza',
                'sesiones' => [
                    ['fecha' => '2025-11-08', 'dia' => 'Sábado', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '13:30:00'],
                    ['fecha' => '2025-11-14', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-11-15', 'dia' => 'Sábado', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-11-21', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-11-22', 'dia' => 'Sábado', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '13:30:00'],
                ],
            ],
            // MÓDULO 4: CONTABILIDAD
            [
                'nombre' => 'Contabilidad',
                'responsable' => 'Sandra Alvear',
                'sesiones' => [
                    ['fecha' => '2025-11-28', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-11-29', 'dia' => 'Sábado', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-12-05', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-12-06', 'dia' => 'Sábado', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-12-12', 'dia' => 'Viernes', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                ],
            ],
        ];

        $totalClases = 0;
        $totalSesiones = 0;

        foreach ($modulos as $modulo) {
            // Buscar el curso correspondiente
            $curso = Course::where('nombre', $modulo['nombre'])
                ->where('magister_id', $magisterSalud->id)
                ->where('period_id', $periodo->id)
                ->first();

            if (!$curso) {
                $this->command->warn("⚠️ Curso no encontrado: {$modulo['nombre']}");
                continue;
            }

            $responsable = $docentes[$modulo['responsable']];

            // Crear la clase general
            $clase = Clase::create([
                'course_id' => $curso->id,
                'period_id' => $periodo->id,
                'room_id' => $salaFEN1->id,
                'encargado' => $modulo['responsable'],
                'tipo' => 'cátedra',
                'url_zoom' => $zoomUrl,
            ]);

            $totalClases++;

            // Crear las sesiones individuales
            foreach ($modulo['sesiones'] as $index => $sesion) {
                $dataSesion = [
                    'clase_id' => $clase->id,
                    'fecha' => $sesion['fecha'],
                    'dia' => $sesion['dia'],
                    'hora_inicio' => $sesion['hora_inicio'],
                    'hora_fin' => $sesion['hora_fin'],
                    'modalidad' => $sesion['modalidad'],
                    'room_id' => $sesion['modalidad'] !== 'online' ? $salaFEN1->id : null,
                    'url_zoom' => $zoomUrl,
                    'observaciones' => $sesion['modalidad'] === 'online' 
                        ? "Clase online vía Zoom\n{$zoomInfo}" 
                        : "Clase híbrida: Presencial en FEN 1 + transmisión online\n{$zoomInfo}",
                    'numero_sesion' => $index + 1,
                    'estado' => 'pendiente',
                ];

                // Si es SÁBADO y dura todo el día (09:00 - 16:30), agregar bloques horarios
                if ($sesion['dia'] === 'Sábado' && $sesion['hora_inicio'] === '09:00:00' && $sesion['hora_fin'] === '16:30:00') {
                    $dataSesion['bloques_horarios'] = json_encode([
                        ['tipo' => 'clase', 'inicio' => '09:00', 'fin' => '10:30', 'nombre' => ''],
                        ['tipo' => 'coffee_break', 'inicio' => '10:30', 'fin' => '11:00', 'nombre' => ''],
                        ['tipo' => 'clase', 'inicio' => '11:00', 'fin' => '13:30', 'nombre' => ''],
                        ['tipo' => 'lunch_break', 'inicio' => '13:30', 'fin' => '14:30', 'nombre' => ''],
                        ['tipo' => 'clase', 'inicio' => '14:30', 'fin' => '15:30', 'nombre' => ''],
                        ['tipo' => 'clase', 'inicio' => '15:30', 'fin' => '16:30', 'nombre' => ''],
                    ]);
                }
                // Si es SÁBADO medio día (09:00 - 13:30)
                elseif ($sesion['dia'] === 'Sábado' && $sesion['hora_inicio'] === '09:00:00' && $sesion['hora_fin'] === '13:30:00') {
                    $dataSesion['bloques_horarios'] = json_encode([
                        ['tipo' => 'clase', 'inicio' => '09:00', 'fin' => '10:30', 'nombre' => ''],
                        ['tipo' => 'coffee_break', 'inicio' => '10:30', 'fin' => '11:00', 'nombre' => ''],
                        ['tipo' => 'clase', 'inicio' => '11:00', 'fin' => '13:30', 'nombre' => ''],
                    ]);
                }

                ClaseSesion::create($dataSesion);

                $totalSesiones++;
            }
        }

        $this->command->info("✅ Se crearon $totalClases clases y $totalSesiones sesiones del 1er Trimestre 2025 - Magíster en Gestión de Sistemas de Salud.");
    }
}

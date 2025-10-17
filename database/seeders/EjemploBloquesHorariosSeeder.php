<?php

namespace Database\Seeders;

use App\Models\Clase;
use App\Models\ClaseSesion;
use Illuminate\Database\Seeder;

/**
 * Seeder de ejemplo para demostrar el uso de bloques horarios
 * 
 * Este seeder NO se ejecuta automáticamente.
 * Para ejecutarlo: php artisan db:seed --class=EjemploBloquesHorariosSeeder
 */
class EjemploBloquesHorariosSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar una clase existente (ajusta el ID según tu DB)
        $clase = Clase::first();
        
        if (!$clase) {
            $this->command->error('No hay clases en la base de datos. Crea una primero.');
            return;
        }

        $this->command->info('🎯 Creando sesiones de ejemplo con bloques horarios...');

        // Ejemplo 1: Sábado completo con template estándar
        $sesion1 = ClaseSesion::create([
            'clase_id' => $clase->id,
            'fecha' => now()->next('Saturday'),
            'dia' => 'Sábado',
            'estado' => 'pendiente',
            'observaciones' => 'Sesión ejemplo con template sábado completo',
            'bloques_horarios' => [
                ['tipo' => 'clase', 'inicio' => '09:00', 'fin' => '10:30', 'nombre' => ''],
                ['tipo' => 'coffee_break', 'inicio' => '10:30', 'fin' => '11:00', 'nombre' => ''],
                ['tipo' => 'clase', 'inicio' => '11:00', 'fin' => '13:30', 'nombre' => ''],
                ['tipo' => 'lunch_break', 'inicio' => '13:30', 'fin' => '14:30', 'nombre' => ''],
                ['tipo' => 'clase', 'inicio' => '14:30', 'fin' => '15:30', 'nombre' => ''],
                ['tipo' => 'clase', 'inicio' => '15:30', 'fin' => '16:30', 'nombre' => ''],
            ]
        ]);

        $this->command->info('✅ Sesión 1 creada: Sábado completo (6h clase + breaks)');

        // Ejemplo 2: Clase nocturna sin breaks
        $sesion2 = ClaseSesion::create([
            'clase_id' => $clase->id,
            'fecha' => now()->next('Friday'),
            'dia' => 'Viernes',
            'hora_inicio' => '19:00:00',
            'hora_fin' => '22:00:00',
            'modalidad' => 'presencial',
            'estado' => 'pendiente',
            'observaciones' => 'Clase nocturna - modo tradicional sin bloques',
        ]);

        $this->command->info('✅ Sesión 2 creada: Viernes nocturno (sin bloques)');

        // Ejemplo 3: Workshop con break único
        $sesion3 = ClaseSesion::create([
            'clase_id' => $clase->id,
            'fecha' => now()->addDays(10),
            'dia' => 'Sábado',
            'estado' => 'pendiente',
            'observaciones' => 'Workshop intensivo',
            'bloques_horarios' => [
                ['tipo' => 'clase', 'inicio' => '09:00', 'fin' => '12:00', 'nombre' => 'Módulo 1'],
                ['tipo' => 'lunch_break', 'inicio' => '12:00', 'fin' => '13:00', 'nombre' => ''],
                ['tipo' => 'clase', 'inicio' => '13:00', 'fin' => '17:00', 'nombre' => 'Módulo 2'],
            ]
        ]);

        $this->command->info('✅ Sesión 3 creada: Workshop con lunch break');

        // Ejemplo 4: Sesión con múltiples breaks
        $sesion4 = ClaseSesion::create([
            'clase_id' => $clase->id,
            'fecha' => now()->addDays(17),
            'dia' => 'Sábado',
            'estado' => 'pendiente',
            'observaciones' => 'Jornada extendida con descansos frecuentes',
            'bloques_horarios' => [
                ['tipo' => 'clase', 'inicio' => '09:00', 'fin' => '10:30', 'nombre' => ''],
                ['tipo' => 'coffee_break', 'inicio' => '10:30', 'fin' => '10:45', 'nombre' => ''],
                ['tipo' => 'clase', 'inicio' => '10:45', 'fin' => '12:30', 'nombre' => ''],
                ['tipo' => 'lunch_break', 'inicio' => '12:30', 'fin' => '13:30', 'nombre' => ''],
                ['tipo' => 'clase', 'inicio' => '13:30', 'fin' => '15:00', 'nombre' => ''],
                ['tipo' => 'coffee_break', 'inicio' => '15:00', 'fin' => '15:15', 'nombre' => ''],
                ['tipo' => 'clase', 'inicio' => '15:15', 'fin' => '17:00', 'nombre' => ''],
            ]
        ]);

        $this->command->info('✅ Sesión 4 creada: Jornada extendida con múltiples breaks');

        // Mostrar resumen
        $this->command->newLine();
        $this->command->info('📊 RESUMEN:');
        $this->command->table(
            ['Sesión', 'Fecha', 'Tiene Bloques', 'Duración Clase'],
            [
                ['#1 Sábado completo', $sesion1->fecha->format('d/m/Y'), 'Sí', $sesion1->duracion_clase_minutos . ' min'],
                ['#2 Viernes nocturno', $sesion2->fecha->format('d/m/Y'), 'No', '180 min'],
                ['#3 Workshop', $sesion3->fecha->format('d/m/Y'), 'Sí', $sesion3->duracion_clase_minutos . ' min'],
                ['#4 Jornada extendida', $sesion4->fecha->format('d/m/Y'), 'Sí', $sesion4->duracion_clase_minutos . ' min'],
            ]
        );

        $this->command->newLine();
        $this->command->info('🎉 Sesiones de ejemplo creadas exitosamente!');
        $this->command->info('📝 Puedes verlas en: ' . route('clases.show', $clase));
    }
}


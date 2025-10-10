<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔒 Desactivar claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 🧹 Limpiar las tablas en orden seguro (dependencias al final)
        DB::table('incidents')->truncate();      // depende de rooms
        DB::table('rooms')->truncate();
        DB::table('courses')->truncate();        // depende de magisters y mallas
        DB::table('malla_curriculars')->truncate(); // depende de magisters
        DB::table('magisters')->truncate();
        DB::table('users')->truncate();          // solo si quieres resetear usuarios
        DB::table('periods')->truncate(); 
        DB::table('clases')->truncate();         
        DB::table('clase_sesiones')->truncate();
        DB::table('staff')->truncate(); 
        DB::table('events')->truncate();
        DB::table('emergencies')->truncate();
        DB::table('informes')->truncate();
        DB::table('novedades')->truncate();
         
        // si tiene relaciones también
        // Añade más tablas si es necesario

        // 🔒 Reactivar claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🌱 Ejecutar seeders en orden de dependencias
        $this->call([
            PeriodSeeder::class,               // 1. Primero los periodos
            UsersTableSeeder::class,           // 2. Usuarios
            MagistersTableSeeder::class,       // 3. Magísteres
            MallasCurricularesSeeder::class,   // 4. Mallas (depende de magisters)
            CoursesTableSeeder::class,         // 5. Cursos (depende de magisters, mallas y periods)
            RoomsTableSeeder::class,           // 6. Salas
            ClaseSeeder::class,                // 7. Clases (depende de courses, periods, rooms)
            IncidentsTableSeeder::class,       // 8. Incidencias (depende de rooms)
            StaffSeeder::class,                // 9. Staff
            EventSeeder::class,                // 10. Eventos
            NovedadesSeeder::class,            // 11. Novedades
        ]);
    }
}

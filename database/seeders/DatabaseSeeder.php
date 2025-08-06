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
        DB::table('courses')->truncate();        // depende de magisters
        DB::table('magisters')->truncate();
        DB::table('users')->truncate();          // solo si quieres resetear usuarios
        DB::table('periods')->truncate(); 
        //DB::table('room_usages')->truncate();    //
        DB::table('clases')->truncate();         //
        // si tiene relaciones también
        // Añade más tablas si es necesario

        // 🔒 Reactivar claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🌱 Ejecutar seeders
        $this->call([
            PeriodSeeder::class,
            UsersTableSeeder::class,
            MagistersTableSeeder::class,
            CoursesTableSeeder::class,
            RoomsTableSeeder::class,
            IncidentsTableSeeder::class,
          //  RoomUsageSeeder::class, // 👈 Agregado aquí
            ClaseSeeder::class,
        ]);
    }
}

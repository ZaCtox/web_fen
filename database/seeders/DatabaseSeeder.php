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

        // 🌱 Ejecutar seeder consolidado
        $this->call([
            MagisterSaludSeeder::class,        // Seeder unificado con todos los datos del Magíster en Gestión de Sistemas de Salud
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔒 Limpiar tablas respetando relaciones FK
        $tables = [
            'incidents',
            'rooms',
            'courses',
            'magisters',
            'users',
            'periods',
            'clases',
            'staff',
            'events',
        ];

        foreach ($tables as $table) {
            DB::statement("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE");
        }

        // 🌱 Ejecutar seeders
        $this->call([
            PeriodSeeder::class,
            UsersTableSeeder::class,
            MagistersTableSeeder::class,
            CoursesTableSeeder::class,
            RoomsTableSeeder::class,
            IncidentsTableSeeder::class,
            ClaseSeeder::class,
            StaffSeeder::class,
            EventSeeder::class,
        ]);
    }
}
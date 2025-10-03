<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Usuario Administrador
        User::firstOrCreate([
            'email' => 'admin@webfen.cl',
        ], [
            'name' => 'Administrador FEN',
            'password' => Hash::make('admin123'),
            'rol' => 'administrador',
        ]);

        // Usuario Docente
        User::firstOrCreate([
            'email' => 'docente@webfen.cl',
        ], [
            'name' => 'Docente de Prueba',
            'password' => Hash::make('docente123'),
            'rol' => 'docente',
        ]);

        // Usuario Asistente de Programa
        User::firstOrCreate([
            'email' => 'asistente.programa@webfen.cl',
        ], [
            'name' => 'Asistente de Programa',
            'password' => Hash::make('asistente123'),
            'rol' => 'asistente_programa',
        ]);

        // Usuario Asistente de Postgrado
        User::firstOrCreate([
            'email' => 'asistente.postgrado@webfen.cl',
        ], [
            'name' => 'Asistente Postgrado',
            'password' => Hash::make('postgrado123'),
            'rol' => 'asistente_postgrado',
        ]);

        // Usuario Director de Magíster
        User::firstOrCreate([
            'email' => 'director.programa@webfen.cl',
        ], [
            'name' => 'Director de Programa',
            'password' => Hash::make('director123'),
            'rol' => 'director_programa',
        ]);

        // Usuario Auxiliar
        User::firstOrCreate([
            'email' => 'auxiliar@webfen.cl',
        ], [
            'name' => 'Auxiliar FEN',
            'password' => Hash::make('auxiliar123'),
            'rol' => 'auxiliar',
        ]);

        // Usuario Director Administrativo
        User::firstOrCreate([
            'email' => 'director.admin@webfen.cl',
        ], [
            'name' => 'Director Administrativo',
            'password' => Hash::make('admin456'),
            'rol' => 'director_administrativo',
        ]);

        // Usuario Técnico (por rutas de incidencias)
        User::firstOrCreate([
            'email' => 'tecnico@webfen.cl',
        ], [
            'name' => 'Técnico FEN',
            'password' => Hash::make('tecnico123'),
            'rol' => 'técnico',
        ]);
    }
}

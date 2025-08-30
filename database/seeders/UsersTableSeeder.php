<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Usuario Administrativo
        User::firstOrCreate([
            'email' => 'admin@webfen.cl',
        ], [
            'name' => 'Administrador FEN',
            'password' => Hash::make('admin123'),
            'rol' => 'administrativo',
        ]);

        // Usuario Docente
        User::firstOrCreate([
            'email' => 'docente@webfen.cl',
        ], [
            'name' => 'Docente de Prueba',
            'password' => Hash::make('docente123'),
            'rol' => 'docente',
        ]);

        // Usuario Asistente
        User::firstOrCreate([
            'email' => 'asistente@webfen.cl',
        ], [
            'name' => 'Asistente FEN',
            'password' => Hash::make('asistente123'),
            'rol' => 'asistente',
        ]);

        // Usuario Director de Magíster
        User::firstOrCreate([
            'email' => 'director.magister@webfen.cl',
        ], [
            'name' => 'Director de Magíster',
            'password' => Hash::make('director123'),
            'rol' => 'director_magister',
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
    }
}

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
    }
}

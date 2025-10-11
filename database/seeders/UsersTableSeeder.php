<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $usuarios = [
            // Administrador del sistema
            [
                'name' => 'Administrador',
                'email' => 'admin@utalca.cl',
                'password' => Hash::make('admin123'),
                'rol' => 'administrador',
            ],
            
            // Director Administrativo
            [
                'name' => 'Director Administrativo',
                'email' => 'director@utalca.cl',
                'password' => Hash::make('admin456'),
                'rol' => 'director_administrativo',
            ],

            // Luis Canales - Director de programa
            [
                'name' => 'Luis Canales',
                'email' => 'lcanales@utalca.cl',
                'password' => Hash::make('luis123'),
                'rol' => 'director_programa',
            ],

            // Mary Sepúlveda - Coordinadora del programa Magíster Salud
            [
                'name' => 'Mary Sepúlveda G.',
                'email' => 'msepulveda@utalca.cl',
                'password' => Hash::make('mary123'),
                'rol' => 'asistente_programa',
            ],

            // María Castillo - Apoyo logístico y operacional
            [
                'name' => 'María Castillo',
                'email' => 'maria.castillob@utalca.cl',
                'password' => Hash::make('maria123'),
                'rol' => 'asistente_postgrado',
            ],
        ];

        foreach ($usuarios as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅ Usuarios principales creados correctamente.');
    }
}

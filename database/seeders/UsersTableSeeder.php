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
                'name' => 'Arcadio Cerda',
                'email' => 'acerda@utalca.cl',
                'password' => Hash::make('admin123'),
                'rol' => 'administrador',
            ],
            
            // Director Administrativo
            [
                'name' => 'José Leonardo Castillo',
                'email' => 'josecastillo@utalca.cl',
                'password' => Hash::make('admin456'),
                'rol' => 'director_administrativo',
            ],

            // Docentes de Economía
            [
                'name' => 'Dr. Fernando Muñoz González',
                'email' => 'fmunoz@utalca.cl',
                'password' => Hash::make('docente123'),
                'rol' => 'docente',
            ],
            [
                'name' => 'Dra. Claudia Sepúlveda Rojas',
                'email' => 'csepulveda@utalca.cl',
                'password' => Hash::make('docente123'),
                'rol' => 'docente',
            ],
            [
                'name' => 'Dr. Andrés Villalobos Cid',
                'email' => 'avillalobos@utalca.cl',
                'password' => Hash::make('docente123'),
                'rol' => 'docente',
            ],

            // Docentes de Gestión y Finanzas
            [
                'name' => 'Dra. María José Retamal Silva',
                'email' => 'mjretamal@utalca.cl',
                'password' => Hash::make('docente123'),
                'rol' => 'docente',
            ],
            [
                'name' => 'Dr. Roberto Contreras Fuentes',
                'email' => 'rcontreras@utalca.cl',
                'password' => Hash::make('docente123'),
                'rol' => 'docente',
            ],

            // Directores de Programa
            [
                'name' => 'Dr. Patricio Aroca Gonzalez',
                'email' => 'paroca@utalca.cl',
                'password' => Hash::make('director123'),
                'rol' => 'director_programa',
            ],
            [
                'name' => 'Dra. Verónica Mies Moreno',
                'email' => 'vmies@utalca.cl',
                'password' => Hash::make('director123'),
                'rol' => 'director_programa',
            ],
            [
                'name' => 'Luis Canales',
                'email' => 'lcanales@utalca.cl',
                'password' => Hash::make('director123'),
                'rol' => 'director_programa',
            ],

            // Asistentes de Programa
            [
                'name' => 'Carolina Balmaceda Rojas',
                'email' => 'cbalmaceda@utalca.cl',
                'password' => Hash::make('asistente123'),
                'rol' => 'asistente_programa',
            ],
            [
                'name' => 'Carolina Quitral',
                'email' => 'cquitral@utalca.cl',
                'password' => Hash::make('asistente123'),
                'rol' => 'asistente_programa',
            ],
            [
                'name' => 'Tamara Aravena',
                'email' => 'tamara.aravena@utalca.cl',
                'password' => Hash::make('asistente123'),
                'rol' => 'asistente_programa',
            ],

            // Asistentes de Postgrado
            [
                'name' => 'July Basoalto',
                'email' => 'jbasoalto@utalca.cl',
                'password' => Hash::make('postgrado123'),
                'rol' => 'asistente_postgrado',
            ],
            [
                'name' => 'Ivonne Henríquez Toro',
                'email' => 'ivonne.henriquez@utalca.cl',
                'password' => Hash::make('postgrado123'),
                'rol' => 'asistente_postgrado',
            ],
            [
                'name' => 'Camila González',
                'email' => 'camila.gonzalezd@utalca.cl',
                'password' => Hash::make('postgrado123'),
                'rol' => 'asistente_postgrado',
            ],

            // Técnico de soporte
            [
                'name' => 'Miguel Suárez',
                'email' => 'msuarez@utalca.cl',
                'password' => Hash::make('tecnico123'),
                'rol' => 'técnico',
            ],

            // Auxiliares
            [
                'name' => 'Cristian Barrientos',
                'email' => 'cristian.barrientos@utalca.cl',
                'password' => Hash::make('auxiliar123'),
                'rol' => 'auxiliar',
            ],

            // Personal adicional
            [
                'name' => 'Carolina Cáceres',
                'email' => 'carolina.caceres@utalca.cl',
                'password' => Hash::make('asistente123'),
                'rol' => 'asistente_programa',
            ],
        ];

        foreach ($usuarios as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅ Usuarios creados correctamente con datos realistas de FEN.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomsTableSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            [
                'name' => 'Sala FEN 1',
                'location' => 'Facultad de Economía y Negocios',
                'capacity' => 40,
                'description' => 'Sala principal equipada con proyector, sistema de audio y pizarra interactiva.',
                'calefaccion' => true,
                'energia_electrica' => true,
                'existe_aseo' => true,
                'plumones' => true,
                'borrador' => true,
                'pizarra_limpia' => true,
                'computador_funcional' => true,
                'cables_computador' => true,
                'control_remoto_camara' => true,
                'televisor_funcional' => true,
            ],
            [
                'name' => 'Sala FEN 2',
                'location' => 'Facultad de Economía y Negocios',
                'capacity' => 35,
                'description' => 'Sala para clases de postgrado con proyector y sistema de audio.',
                'calefaccion' => true,
                'energia_electrica' => true,
                'existe_aseo' => true,
                'plumones' => true,
                'borrador' => true,
                'pizarra_limpia' => true,
                'computador_funcional' => true,
                'cables_computador' => true,
                'control_remoto_camara' => false,
                'televisor_funcional' => true,
            ],
            [
                'name' => 'Sala FEN 3',
                'location' => 'Facultad de Economía y Negocios',
                'capacity' => 30,
                'description' => 'Sala multimedia con sistema de videoconferencia. Óptima para clases híbridas.',
                'calefaccion' => true,
                'energia_electrica' => true,
                'existe_aseo' => true,
                'plumones' => true,
                'borrador' => true,
                'pizarra_limpia' => true,
                'computador_funcional' => true,
                'cables_computador' => true,
                'control_remoto_camara' => true,
                'televisor_funcional' => true,
            ],
            [
                'name' => 'Sala 309',
                'location' => 'Facultad de Economía y Negocios',
                'capacity' => 25,
                'description' => 'Sala de clases con equipamiento estándar para grupos pequeños.',
                'calefaccion' => true,
                'energia_electrica' => true,
                'existe_aseo' => false,
                'plumones' => true,
                'borrador' => true,
                'pizarra_limpia' => true,
                'computador_funcional' => true,
                'cables_computador' => true,
                'control_remoto_camara' => false,
                'televisor_funcional' => false,
            ],
        ];

        foreach ($rooms as $roomData) {
            Room::firstOrCreate(
                ['name' => $roomData['name']],
                $roomData
            );
        }

        $this->command->info("✅ 4 salas principales de FEN creadas correctamente.");
    }
}

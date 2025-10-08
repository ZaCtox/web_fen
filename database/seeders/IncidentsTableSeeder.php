<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;
use App\Models\Room;
use App\Models\Period;
use Illuminate\Support\Carbon;

class IncidentsTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $rooms = Room::all();
        $periods = Period::all();

        if ($users->isEmpty() || $rooms->isEmpty() || $periods->isEmpty()) {
            $this->command->warn('⚠️ Faltan datos: asegúrate de tener usuarios, salas y períodos cargados.');
            return;
        }

        $incidencias = [
            // Problemas eléctricos
            [
                'titulo' => 'Tubos fluorescentes quemados en sector posterior',
                'descripcion' => 'Se reportan 3 tubos fluorescentes sin funcionamiento en la zona posterior de la sala, lo que genera baja iluminación para los estudiantes en esa área.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Tomacorrientes sin energía en muro izquierdo',
                'descripcion' => 'Los enchufes del lado izquierdo de la sala no proporcionan energía eléctrica. Los estudiantes no pueden conectar sus laptops durante las clases.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Interruptor de luces defectuoso',
                'descripcion' => 'El interruptor principal de la sala requiere múltiples intentos para encender las luces. Se solicita revisión eléctrica.',
                'estado' => 'en_revision',
            ],
            
            // Problemas de climatización
            [
                'titulo' => 'Aire acondicionado no enfría adecuadamente',
                'descripcion' => 'El sistema de aire acondicionado está funcionando pero no logra bajar la temperatura ambiente. Se solicita revisión técnica del equipo.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Ruido excesivo en sistema de ventilación',
                'descripcion' => 'El sistema de ventilación genera ruido constante que interfiere con el desarrollo normal de las clases. Se requiere mantención.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Calefacción no enciende',
                'descripcion' => 'Durante los días fríos, el sistema de calefacción no se activa. La temperatura en la sala es muy baja para realizar clases cómodamente.',
                'estado' => 'no_resuelta',
            ],
            
            // Problemas de mobiliario
            [
                'titulo' => 'Sillas con rodachines en mal estado',
                'descripcion' => 'Varias sillas presentan rodachines rotos o faltantes, lo que dificulta su desplazamiento y genera ruidos molestos durante las clases.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Mesa de profesor inestable',
                'descripcion' => 'La mesa del docente tiene una pata floja que genera inestabilidad. Se requiere reparación o reemplazo urgente.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Sillas insuficientes para capacidad de la sala',
                'descripcion' => 'La sala tiene capacidad para 35 personas pero solo cuenta con 28 sillas en buen estado. Se requieren 7 sillas adicionales.',
                'estado' => 'pendiente',
            ],
            
            // Problemas de equipamiento tecnológico
            [
                'titulo' => 'Proyector sin imagen - LED de error encendido',
                'descripcion' => 'El proyector no muestra imagen en la pantalla. El LED de error está encendido en color rojo. Se requiere revisión técnica especializada.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Control remoto de proyector extraviado',
                'descripcion' => 'No se encuentra el control remoto del proyector. Se dificulta el encendido y configuración del equipo. Se solicita reposición.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Cable HDMI dañado - no transmite video',
                'descripcion' => 'El cable HDMI de conexión entre el computador y proyector presenta fallas. La imagen se corta constantemente.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Computador no enciende - posible problema de fuente',
                'descripcion' => 'El computador de la sala no responde al presionar el botón de encendido. Se sospecha falla en la fuente de poder.',
                'estado' => 'en_revision',
            ],
            [
                'titulo' => 'Televisor con líneas verticales en pantalla',
                'descripcion' => 'El televisor muestra líneas verticales de colores en toda la pantalla, lo que impide su uso normal para presentaciones.',
                'estado' => 'no_resuelta',
            ],
            [
                'titulo' => 'Micrófono inalámbrico con interferencias',
                'descripcion' => 'El sistema de audio inalámbrico presenta interferencias constantes y cortes de sonido durante las presentaciones.',
                'estado' => 'resuelta',
            ],
            
            // Problemas de conectividad
            [
                'titulo' => 'Red WiFi inestable - desconexiones frecuentes',
                'descripcion' => 'La conexión WiFi en la sala presenta desconexiones constantes, afectando las clases online y el uso de recursos digitales.',
                'estado' => 'en_revision',
            ],
            [
                'titulo' => 'Puerto de red ethernet sin conexión',
                'descripcion' => 'El puerto de red cableada del computador no establece conexión. Se requiere verificación de cableado estructurado.',
                'estado' => 'resuelta',
            ],
            
            // Problemas de infraestructura
            [
                'titulo' => 'Pizarra blanca manchada - no se limpia correctamente',
                'descripcion' => 'La pizarra presenta manchas permanentes de marcadores que no se pueden limpiar con los productos habituales. Se requiere mantención especializada.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Cortinas de ventanas atascadas',
                'descripcion' => 'Las cortinas enrollables no suben ni bajan correctamente. Esto impide controlar la entrada de luz natural durante las proyecciones.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Filtración de agua en ventana durante lluvia',
                'descripcion' => 'Durante días lluviosos se observa filtración de agua por el marco de la ventana, generando humedad en el piso.',
                'estado' => 'pendiente',
            ],
            [
                'titulo' => 'Puerta no cierra correctamente - problema en cerradura',
                'descripcion' => 'La cerradura de la puerta principal está descuadrada. La puerta no cierra bien y se abre sola ocasionalmente.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Ventilación deficiente - ambiente cargado',
                'descripcion' => 'La sala presenta mala ventilación natural. El ambiente se torna cargado rápidamente con grupo completo de estudiantes.',
                'estado' => 'no_resuelta',
            ],
            
            // Problemas de limpieza y mantención
            [
                'titulo' => 'Marcadores de pizarra agotados',
                'descripcion' => 'No hay marcadores de pizarra funcionales en la sala. Se requiere reposición urgente de marcadores azules y negros.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Borrador de pizarra en mal estado',
                'descripcion' => 'El borrador está muy sucio y desgastado, no limpia adecuadamente la pizarra. Se solicita reemplazo.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Basurero sin bolsa y con malos olores',
                'descripcion' => 'El basurero de la sala no tiene bolsa y presenta malos olores. Se requiere limpieza profunda y provisión de bolsas.',
                'estado' => 'resuelta',
            ],
            
            // Problemas de seguridad
            [
                'titulo' => 'Señalética de evacuación desprendida',
                'descripcion' => 'La señalética de ruta de evacuación está desprendida de la pared. Se requiere reinstalación por seguridad.',
                'estado' => 'resuelta',
            ],
            [
                'titulo' => 'Extintor con fecha de mantención vencida',
                'descripcion' => 'Se observa que el extintor de la sala tiene la fecha de mantención vencida desde hace 3 meses. Se requiere inspección urgente.',
                'estado' => 'resuelta',
            ],
        ];

        $comentarios = [
            'resuelta' => [
                'Problema solucionado por el equipo técnico. Se realizó mantención preventiva.',
                'Incidencia resuelta exitosamente. Equipamiento funcionando correctamente.',
                'Se realizó la reparación correspondiente. Sistema operativo.',
                'Problema corregido. Se verificó el correcto funcionamiento.',
                'Mantención realizada. Todo en orden.',
            ],
            'en_revision' => [
                'El técnico está evaluando el problema. Se informará el avance pronto.',
                'Incidencia en proceso de revisión por el área correspondiente.',
                'Se está coordinando con el proveedor para solución técnica.',
                'En espera de repuestos para completar la reparación.',
            ],
            'no_resuelta' => [
                'Problema requiere autorización de inversión mayor. Pendiente de aprobación presupuestaria.',
                'No fue posible resolver. Se requiere reemplazo completo del equipo.',
                'Problema estructural que requiere intervención mayor. Se está evaluando alternativas.',
            ],
        ];

        $anios = [2026, 2025, 2024, 2023];
        $totalCreadas = 0;

        foreach ($anios as $anioSimulado) {
            foreach ($periods as $periodoBase) {
                $fechaInicio = Carbon::parse($periodoBase->fecha_inicio)->year($anioSimulado);
                $fechaFin = Carbon::parse($periodoBase->fecha_fin)->year($anioSimulado);

                if ($fechaFin->lessThan($fechaInicio)) {
                    $fechaFin->addYear();
                }

                // Generar entre 4-6 incidencias por período
                $cantidadIncidencias = rand(4, 6);
                
                for ($i = 0; $i < $cantidadIncidencias; $i++) {
                    $incidencia = $incidencias[array_rand($incidencias)];
                    $fecha = fake()->dateTimeBetween($fechaInicio, $fechaFin);
                    $estado = $incidencia['estado'];
                    
                    // Generar comentarios realistas según el estado
                    $comentario = null;
                    if (isset($comentarios[$estado])) {
                        $comentario = $comentarios[$estado][array_rand($comentarios[$estado])];
                    }

                    // Para incidencias resueltas, agregar fecha de resolución
                    $resueltoEn = null;
                    if ($estado === 'resuelta') {
                        $diasResolucion = rand(1, 15); // Entre 1 y 15 días para resolver
                        $resueltoEn = Carbon::parse($fecha)->addDays($diasResolucion);
                    }

                    Incident::create([
                        'titulo' => $incidencia['titulo'],
                        'descripcion' => $incidencia['descripcion'],
                        'estado' => $estado,
                        'comentario' => $comentario,
                        'created_at' => $fecha,
                        'updated_at' => $fecha,
                        'resuelta_en' => $resueltoEn,
                        'user_id' => $users->random()->id,
                        'room_id' => $rooms->random()->id,
                        'imagen' => null,
                        'public_id' => null,
                        'nro_ticket' => $estado !== 'pendiente' ? 'TICK-' . strtoupper(substr(md5($fecha->format('Y-m-d H:i:s') . $i), 0, 8)) : null,
                    ]);

                    $totalCreadas++;
                }
            }
        }

        $this->command->info("✅ Se generaron $totalCreadas incidencias realistas (2023-2026).");
    }
}

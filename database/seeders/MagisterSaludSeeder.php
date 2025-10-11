<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Magister;
use App\Models\MallaCurricular;
use App\Models\Period;
use App\Models\Course;
use App\Models\Room;
use App\Models\Clase;
use App\Models\ClaseSesion;
use App\Models\Staff;
use App\Models\Novedad;

class MagisterSaludSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏥 Iniciando creación de datos para Magíster en Gestión de Sistemas de Salud...');

        // ==========================================
        // 1. USUARIOS
        // ==========================================
        $this->command->info('👥 Creando usuarios...');
        
        $usuarios = [
            'admin' => User::firstOrCreate(
                ['email' => 'acerda@utalca.cl'],
                ['name' => 'Arcadio Cerda', 'password' => Hash::make('admin123'), 'rol' => 'administrador']
            ),
            'director_admin' => User::firstOrCreate(
                ['email' => 'director@utalca.cl'],
                ['name' => 'Director Administrativo', 'password' => Hash::make('admin456'), 'rol' => 'director_administrativo']
            ),
            'luis_canales' => User::firstOrCreate(
                ['email' => 'lcanales@utalca.cl'],
                ['name' => 'Luis Canales', 'password' => Hash::make('luis123'), 'rol' => 'director_programa']
            ),
            'mary_sepulveda' => User::firstOrCreate(
                ['email' => 'msepulveda@utalca.cl'],
                ['name' => 'Mary Sepúlveda G.', 'password' => Hash::make('mary123'), 'rol' => 'asistente_programa']
            ),
            'maria_castillo' => User::firstOrCreate(
                ['email' => 'maria.castillob@utalca.cl'],
                ['name' => 'María Castillo', 'password' => Hash::make('maria123'), 'rol' => 'asistente_postgrado']
            ),
        ];

        // Docentes
        $docentes = [
            'margarita_pereira' => User::firstOrCreate(
                ['email' => 'mpereira@utalca.cl'],
                ['name' => 'Margarita Pereira', 'password' => Hash::make('docente123'), 'rol' => 'docente']
            ),
            'andres_riquelme' => User::firstOrCreate(
                ['email' => 'ariquelme@utalca.cl'],
                ['name' => 'Andrés Riquelme', 'password' => Hash::make('docente123'), 'rol' => 'docente']
            ),
            'milton_inostroza' => User::firstOrCreate(
                ['email' => 'minostroza@utalca.cl'],
                ['name' => 'Milton Inostroza', 'password' => Hash::make('docente123'), 'rol' => 'docente']
            ),
            'sandra_alvear' => User::firstOrCreate(
                ['email' => 'salvear@utalca.cl'],
                ['name' => 'Sandra Alvear', 'password' => Hash::make('docente123'), 'rol' => 'docente']
            ),
        ];

        $this->command->info('   ✅ ' . (count($usuarios) + count($docentes)) . ' usuarios creados');

        // ==========================================
        // 2. MAGÍSTER
        // ==========================================
        $this->command->info('🎓 Creando Magíster...');
        
        $magister = Magister::firstOrCreate(
            ['nombre' => 'Gestión de Sistemas de Salud'],
            [
                'color' => '#10b981',
                'orden' => 1,
                'encargado' => 'Luis Canales',
                'asistente' => 'Mary Isabel Sepúlveda G.',
                'telefono' => '+56 712200313',
                'anexo' => '0313',
                'correo' => 'msepulveda@utalca.cl'
            ]
        );

        $this->command->info('   ✅ Magíster creado');

        // ==========================================
        // 3. MALLAS CURRICULARES
        // ==========================================
        $this->command->info('📚 Creando Mallas Curriculares...');
        
        $mallas = [
            MallaCurricular::firstOrCreate(
                ['codigo' => 'GSS-2024-V1'],
                [
                    'magister_id' => $magister->id,
                    'nombre' => 'Malla Curricular 2024-2025',
                    'año_inicio' => 2024,
                    'año_fin' => 2025,
                    'activa' => false,
                    'descripcion' => 'Malla curricular para la cohorte 2024-2025 del Magíster en Gestión de Sistemas de Salud.'
                ]
            ),
            MallaCurricular::firstOrCreate(
                ['codigo' => 'GSS-2025-V2'],
                [
                    'magister_id' => $magister->id,
                    'nombre' => 'Malla Curricular 2025-2026',
                    'año_inicio' => 2025,
                    'año_fin' => 2026,
                    'activa' => true,
                    'descripcion' => 'Malla curricular vigente con énfasis en transformación digital, gestión hospitalaria moderna y políticas públicas en salud.'
                ]
            ),
        ];

        $this->command->info('   ✅ 2 mallas curriculares creadas');

        // ==========================================
        // 4. PERÍODOS
        // ==========================================
        $this->command->info('📅 Creando Períodos Académicos...');
        
        $periodos = [];
        $cohorte = '2025-2026';
        
        // Crear períodos para la cohorte 2025-2026
        foreach ([1, 2] as $anio) {
            foreach ([1, 2, 3] as $trimestre) {
                $periodo = Period::firstOrCreate(
                    [
                        'cohorte' => $cohorte,
                        'anio' => $anio,
                        'numero' => $trimestre,
                    ],
                    [
                        'fecha_inicio' => Carbon::now()->addMonths(($anio - 1) * 12 + ($trimestre - 1) * 4),
                        'fecha_fin' => Carbon::now()->addMonths(($anio - 1) * 12 + $trimestre * 4),
                    ]
                );
                $periodos[] = $periodo;
            }
        }

        $this->command->info('   ✅ ' . count($periodos) . ' períodos creados para cohorte ' . $cohorte);

        // ==========================================
        // 5. CURSOS
        // ==========================================
        $this->command->info('📖 Creando Cursos de la Malla 2025-2026...');
        
        $mallaActual = $mallas[1]; // Malla 2025-2026
        
        // Definir todos los cursos por año y trimestre
        $cursosPorPeriodo = [
            // Año 1 - Trimestre I
            [1, 1, 'Taller 1 – Habilidades de Aprendizaje: Presentación Efectiva, Trabajo en Equipo, Metodología de Casos'],
            [1, 1, 'Economía'],
            [1, 1, 'Contabilidad'],
            [1, 1, 'Administración'],
            
            // Año 1 - Trimestre II
            [1, 2, 'Estadística para la Gestión'],
            [1, 2, 'Entorno Económico'],
            [1, 2, 'Entorno Social Cultural'],
            [1, 2, 'Taller 2: Herramientas para el Trabajo de Grado: Métodos y Técnicas para la Investigación en Gestión'],
            
            // Año 1 - Trimestre III
            [1, 3, 'Aspectos Legales en Salud'],
            [1, 3, 'Desarrollo de Competencias Relacionales'],
            [1, 3, 'Dirección Estratégica de Sistemas de Salud'],
            [1, 3, 'Sistema de Salud y Gestión en Red'],
            
            // Año 2 - Trimestre I
            [2, 1, 'Dirección Estratégica de Recursos Humanos'],
            [2, 1, 'Gestión de Operaciones, Logística y Calidad'],
            [2, 1, 'Epidemiología y Salud Pública para la Gestión'],
            [2, 1, 'Trabajo de Grado I'],
            
            // Año 2 - Trimestre II
            [2, 2, 'Calidad y Acreditación en Salud'],
            [2, 2, 'Formulación y Evaluación de Proyectos en Salud'],
            [2, 2, 'Control Estratégico de Instituciones de Salud'],
            [2, 2, 'Trabajo de Grado II'],
            
            // Año 2 - Trimestre III
            [2, 3, 'Electivo I'],
            [2, 3, 'Electivo II'],
            [2, 3, 'Taller 3: Desarrollo y Crecimiento Personal'],
            [2, 3, 'Trabajo de Grado III'],
        ];

        $cursosCreados = [];
        foreach ($cursosPorPeriodo as [$anio, $trimestre, $nombreCurso]) {
            $periodo = Period::where('cohorte', '2025-2026')
                ->where('anio', $anio)
                ->where('numero', $trimestre)
                ->first();
            
            if ($periodo) {
                $curso = Course::firstOrCreate(
                    [
                        'nombre' => $nombreCurso,
                        'magister_id' => $magister->id,
                        'period_id' => $periodo->id,
                    ],
                    ['malla_curricular_id' => $mallaActual->id]
                );
                $cursosCreados[] = $curso;
            }
        }

        $this->command->info('   ✅ ' . count($cursosCreados) . ' cursos creados (todos los trimestres del Año 1 y 2)');

        // ==========================================
        // 6. SALAS
        // ==========================================
        $this->command->info('🏛️ Creando Salas...');
        
        $salaFEN1 = Room::firstOrCreate(
            ['name' => 'Sala FEN 1'],
            [
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
            ]
        );

        $this->command->info('   ✅ Sala FEN 1 creada');

        // ==========================================
        // 7. CLASES Y SESIONES
        // ==========================================
        $this->command->info('🗓️ Creando Clases y Sesiones...');
        
        $zoomUrl = 'https://reuna.zoom.us/j/82980173545';
        $zoomInfo = 'ID: 829 8017 3545 | Código: 712683';

        // Obtener el período del 1er trimestre y los cursos correspondientes
        $periodo1erTrimestre = Period::where('cohorte', '2025-2026')->where('anio', 1)->where('numero', 1)->first();
        
        // Buscar los cursos del 1er trimestre por nombre
        $cursoTaller1 = collect($cursosCreados)->first(fn($c) => str_contains($c->nombre, 'Taller 1'));
        $cursoEconomia = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Economía');
        $cursoContabilidad = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Contabilidad');
        $cursoAdministracion = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Administración');
        
        $modulos = [
            [
                'curso' => $cursoTaller1,
                'responsable' => $docentes['margarita_pereira'],
                'sesiones' => [
                    ['fecha' => '2025-10-03', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-10-04', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-10-10', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-10-11', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                ],
            ],
            [
                'curso' => $cursoEconomia,
                'responsable' => $docentes['andres_riquelme'],
                'sesiones' => [
                    ['fecha' => '2025-10-17', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-10-18', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-10-24', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-10-25', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-11-07', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                ],
            ],
            [
                'curso' => $cursoAdministracion,
                'responsable' => $docentes['milton_inostroza'],
                'sesiones' => [
                    ['fecha' => '2025-11-08', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '13:30:00'],
                    ['fecha' => '2025-11-14', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-11-15', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-11-21', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-11-22', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '13:30:00'],
                ],
            ],
            [
                'curso' => $cursoContabilidad,
                'responsable' => $docentes['sandra_alvear'],
                'sesiones' => [
                    ['fecha' => '2025-11-28', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-11-29', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-12-05', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                    ['fecha' => '2025-12-06', 'modalidad' => 'híbrida', 'hora_inicio' => '09:00:00', 'hora_fin' => '16:30:00'],
                    ['fecha' => '2025-12-12', 'modalidad' => 'online', 'hora_inicio' => '18:30:00', 'hora_fin' => '21:30:00'],
                ],
            ],
        ];

        $totalClases = 0;
        $totalSesiones = 0;
        
        foreach ($modulos as $modulo) {
            // Crear UNA clase por MÓDULO (no por sesión)
            $clase = Clase::firstOrCreate(
                [
                    'course_id' => $modulo['curso']->id,
                    'period_id' => $periodo1erTrimestre->id,
                ],
                [
                    'room_id' => $salaFEN1->id, // Sala por defecto (la mayoría usa FEN 1)
                    'url_zoom' => $zoomUrl,     // Zoom por defecto
                    'encargado' => $modulo['responsable']->name, // Nombre del encargado
                ]
            );

            $totalClases++;

            // Crear las SESIONES individuales para este módulo
            foreach ($modulo['sesiones'] as $index => $sesion) {
                // Determinar el día basado en la fecha
                $dia = date('l', strtotime($sesion['fecha']));
                $diaEspanol = $dia === 'Friday' ? 'Viernes' : 'Sábado';
                
                $observaciones = $sesion['modalidad'] === 'online' 
                    ? "Clase online vía Zoom ({$sesion['hora_inicio']}-{$sesion['hora_fin']})\n{$zoomInfo}" 
                    : "Clase híbrida: Presencial en FEN 1 + transmisión online ({$sesion['hora_inicio']}-{$sesion['hora_fin']})\n{$zoomInfo}";
                
                ClaseSesion::firstOrCreate(
                    [
                        'clase_id' => $clase->id,
                        'fecha' => $sesion['fecha'],
                    ],
                    [
                        'dia' => $diaEspanol,
                        'hora_inicio' => $sesion['hora_inicio'],
                        'hora_fin' => $sesion['hora_fin'],
                        'modalidad' => $sesion['modalidad'],
                        'room_id' => $sesion['modalidad'] !== 'online' ? $salaFEN1->id : null,
                        'url_zoom' => $sesion['modalidad'] !== 'presencial' ? $zoomUrl : null,
                        'estado' => 'pendiente',
                        'url_grabacion' => null, // Se agregará después de la clase
                        'observaciones' => $observaciones,
                        'numero_sesion' => $index + 1,
                    ]
                );
                $totalSesiones++;
            }
        }

        $this->command->info('   ✅ ' . $totalClases . ' clases y ' . $totalSesiones . ' sesiones creadas');

        // ==========================================
        // 8. STAFF
        // ==========================================
        $this->command->info('👔 Creando Staff...');
        
        $staffData = [
            // Autoridades
            [
                'nombre' => 'Decano FEN',
                'cargo' => 'Decano Facultad de Economía y Negocios',
                'telefono' => '+56 712200300',
                'anexo' => '0300',
                'email' => 'decano.fen@utalca.cl'
            ],
            [
                'nombre' => 'José Leonardo Castillo',
                'cargo' => 'Director Administrativo',
                'telefono' => '+56 712417313',
                'anexo' => '7313',
                'email' => 'josecastillo@utalca.cl'
            ],
            
            // Directores de Magíster
            [
                'nombre' => 'Pablo Neudörfer, PhD',
                'cargo' => 'Director Magíster en Economía',
                'telefono' => '+56 712200350',
                'anexo' => '0350',
                'email' => 'pneudorfer@utalca.cl'
            ],
            [
                'nombre' => 'Dr. Jorge Navarrete',
                'cargo' => 'Director Magíster en Gestión y Políticas Públicas',
                'telefono' => '+56 712200351',
                'anexo' => '0351',
                'email' => 'jnavarrete@utalca.cl'
            ],
            [
                'nombre' => 'Luis Canales',
                'cargo' => 'Director Magíster en Gestión de Sistemas de Salud',
                'telefono' => '+56 712200352',
                'anexo' => '0352',
                'email' => 'lcanales@utalca.cl'
            ],
            
            // Coordinadoras/Asistentes de Programas
            [
                'nombre' => 'July Basoalto Riveros',
                'cargo' => 'Coordinadora Magíster en Economía',
                'telefono' => '+56 712200312',
                'anexo' => '0312',
                'email' => 'jbasoalto@utalca.cl'
            ],
            [
                'nombre' => 'Mary Isabel Sepúlveda G.',
                'cargo' => 'Coordinadora Magíster en Gestión y Políticas Públicas',
                'telefono' => '+56 712200313',
                'anexo' => '0313',
                'email' => 'msepulveda@utalca.cl'
            ],
            
            // Oficina de Postgrado
            [
                'nombre' => 'Patricia Muñoz',
                'cargo' => 'Jefa Oficina de Postgrado',
                'telefono' => '+56 712201514',
                'anexo' => '1514',
                'email' => 'pamunoz@utalca.cl'
            ],
            [
                'nombre' => 'María Castillo',
                'cargo' => 'Asistente de Postgrado - Apoyo Logístico y Operacional',
                'telefono' => '+56 712200314',
                'anexo' => '0314',
                'email' => 'maria.castillob@utalca.cl'
            ],
            
            // Docentes del Magíster en Gestión de Sistemas de Salud
            [
                'nombre' => 'Margarita Pereira',
                'cargo' => 'Docente - Habilidades de Aprendizaje',
                'telefono' => '+56 712200360',
                'anexo' => '0360',
                'email' => 'mpereira@utalca.cl'
            ],
            [
                'nombre' => 'Andrés Riquelme',
                'cargo' => 'Docente - Economía',
                'telefono' => '+56 712200361',
                'anexo' => '0361',
                'email' => 'ariquelme@utalca.cl'
            ],
            [
                'nombre' => 'Milton Inostroza',
                'cargo' => 'Docente - Administración',
                'telefono' => '+56 712200362',
                'anexo' => '0362',
                'email' => 'minostroza@utalca.cl'
            ],
            [
                'nombre' => 'Sandra Alvear',
                'cargo' => 'Docente - Contabilidad',
                'telefono' => '+56 712200363',
                'anexo' => '0363',
                'email' => 'salvear@utalca.cl'
            ],
        ];

        foreach ($staffData as $staff) {
            Staff::firstOrCreate(
                ['email' => $staff['email']],
                $staff
            );
        }

        $this->command->info('   ✅ ' . count($staffData) . ' miembros del staff creados');

        // ==========================================
        // 9. NOVEDADES
        // ==========================================
        $this->command->info('📰 Creando Novedades...');
        
        $novedades = [
            [
                'titulo' => 'Inicio de Postulaciones - Magíster en Gestión de Sistemas de Salud',
                'contenido' => 'Se encuentran abiertas las postulaciones para el Magíster en Gestión de Sistemas de Salud, cohorte 2025-2026. Revisa los requisitos y documentación necesaria en nuestra página web. ¡No pierdas esta oportunidad!',
                'tipo_novedad' => 'admision',
                'visible_publico' => true,
                'magister_id' => $magister->id,
                'es_urgente' => true,
                'color' => 'green',
                'icono' => '📝',
                'fecha_expiracion' => Carbon::create(2026, 7, 10),
            ],
            [
                'titulo' => 'Término de Postulaciones - Magíster en Gestión de Sistemas de Salud',
                'contenido' => 'Recordatorio: Las postulaciones para el Magíster en Gestión de Sistemas de Salud cierran el 10 de julio de 2026. Asegúrate de completar todos los documentos requeridos antes de esta fecha.',
                'tipo_novedad' => 'admision',
                'visible_publico' => true,
                'magister_id' => $magister->id,
                'es_urgente' => true,
                'color' => 'red',
                'icono' => '⏰',
                'fecha_expiracion' => Carbon::create(2026, 7, 10),
            ],
            [
                'titulo' => 'Inicio de Clases - Magíster en Gestión de Sistemas de Salud',
                'contenido' => 'Las clases del Magíster en Gestión de Sistemas de Salud cohorte 2025-2026 dan inicio el 17 de julio de 2026. ¡Te esperamos para comenzar esta nueva etapa académica!',
                'tipo_novedad' => 'academica',
                'visible_publico' => true,
                'magister_id' => $magister->id,
                'es_urgente' => false,
                'color' => 'blue',
                'icono' => '🎓',
                'fecha_expiracion' => Carbon::create(2026, 8, 31),
            ],
        ];

        foreach ($novedades as $novedadData) {
            $novedadData['user_id'] = $usuarios['admin']->id;
            Novedad::firstOrCreate(
                ['titulo' => $novedadData['titulo']],
                $novedadData
            );
        }

        $this->command->info('   ✅ 3 novedades creadas');

        $this->command->info('');
        $this->command->info('🎉 ¡Datos del Magíster en Gestión de Sistemas de Salud creados exitosamente!');
    }
}


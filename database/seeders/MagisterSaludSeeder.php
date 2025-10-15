<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Magister;
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
                ['email' => 'jcastillo@utalca.cl'],
                ['name' => 'José Leonardo Castillo', 'password' => Hash::make('castillo123'), 'rol' => 'director_administrativo']
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

        // Personal de Apoyo
        $personalApoyo = [
            'cristian_barrientos' => User::firstOrCreate(
                ['email' => 'cristian.barrientos@utalca.cl'],
                ['name' => 'Cristian Barrientos', 'password' => Hash::make('auxiliar123'), 'rol' => 'auxiliar']
            ),
            'miguel_suarez' => User::firstOrCreate(
                ['email' => 'msuarez@utalca.cl'],
                ['name' => 'Miguel Suárez', 'password' => Hash::make('tecnico123'), 'rol' => 'técnico']
            ),
        ];

        $this->command->info('   ✅ ' . (count($usuarios) + count($docentes) + count($personalApoyo)) . ' usuarios creados');

        // ==========================================
        // 2. MAGÍSTER
        // ==========================================
        $this->command->info('🎓 Creando Programa...');
        
        $magister = Magister::firstOrCreate(
            ['nombre' => 'Gestión de Sistemas de Salud'],
            [
                'color' => '#10b981',
                'orden' => 1,
                'encargado' => 'Luis Canales',
                'asistente' => 'Mary Isabel Sepúlveda G.',
                'telefono' => '+56 712200313',
                'anexo' => '',
                'correo' => 'msepulveda@utalca.cl'
            ]
        );

        $this->command->info('   ✅ Programa creado');

        // ==========================================
        // 3. PERÍODOS
        // ==========================================
        $this->command->info('📅 Creando Períodos Académicos...');
        
        $periodos = [];
        
        // INGRESO 2024 - 6 períodos
        $this->command->info('   📅 Creando períodos para Ingreso 2024...');
        
        $periodos2024 = [
            // Año 1 - Ingreso 2024
            ['anio' => 1, 'numero' => 1, 'anio_ingreso' => 2024, 'fecha_inicio' => '2024-03-11', 'fecha_fin' => '2024-05-31'],
            ['anio' => 1, 'numero' => 2, 'anio_ingreso' => 2024, 'fecha_inicio' => '2024-06-10', 'fecha_fin' => '2024-09-06'],
            ['anio' => 1, 'numero' => 3, 'anio_ingreso' => 2024, 'fecha_inicio' => '2024-09-23', 'fecha_fin' => '2024-12-13'],
            // Año 2 - Ingreso 2024
            ['anio' => 2, 'numero' => 4, 'anio_ingreso' => 2024, 'fecha_inicio' => '2025-03-10', 'fecha_fin' => '2025-05-30'],
            ['anio' => 2, 'numero' => 5, 'anio_ingreso' => 2024, 'fecha_inicio' => '2025-06-09', 'fecha_fin' => '2025-09-05'],
            ['anio' => 2, 'numero' => 6, 'anio_ingreso' => 2024, 'fecha_inicio' => '2025-09-22', 'fecha_fin' => '2025-12-12'],
        ];
        
        foreach ($periodos2024 as $p) {
            $periodo = Period::firstOrCreate(
                [
                    'magister_id' => $magister->id,
                    'anio_ingreso' => $p['anio_ingreso'],
                    'anio' => $p['anio'],
                    'numero' => $p['numero'],
                ],
                [
                    'fecha_inicio' => Carbon::parse($p['fecha_inicio']),
                    'fecha_fin' => Carbon::parse($p['fecha_fin']),
                ]
            );
            $periodos[] = $periodo;
        }
        
        $this->command->info('   ✅ 6 períodos creados para Ingreso 2024');
        
        // INGRESO 2025 - Solo primer trimestre del año 1
        $this->command->info('   📅 Creando períodos para Ingreso 2025 (solo Trimestre 1)...');
        
        $periodos2025 = [
            // Año 1 - Ingreso 2025 (solo trimestre 1)
            ['anio' => 1, 'numero' => 1, 'anio_ingreso' => 2025, 'fecha_inicio' => '2025-09-29', 'fecha_fin' => '2025-12-19'],
        ];
        
        foreach ($periodos2025 as $p) {
            $periodo = Period::firstOrCreate(
                [
                    'magister_id' => $magister->id,
                    'anio_ingreso' => $p['anio_ingreso'],
                    'anio' => $p['anio'],
                    'numero' => $p['numero'],
                ],
                [
                    'fecha_inicio' => Carbon::parse($p['fecha_inicio']),
                    'fecha_fin' => Carbon::parse($p['fecha_fin']),
                ]
            );
            $periodos[] = $periodo;
        }
        
        $this->command->info('   ✅ 1 período creado para Ingreso 2025 (solo Trimestre 1)');
        $this->command->info('   ✅ Total: ' . count($periodos) . ' períodos académicos creados (6 para 2024 + 1 para 2025)');

        // ==========================================
        // 4. CURSOS
        // ==========================================
        $this->command->info('📖 Creando Cursos del Magíster en Gestión de Sistemas de Salud...');
        
        // Definir todos los cursos por año y trimestre con sus SCT y requisitos
        $cursosPorPeriodo = [
            // Año 1 - Trimestre I (requisito: ingreso)
            [1, 1, 'Taller 1 – Habilidades de Aprendizaje: Presentación Efectiva, Trabajo en Equipo, Metodología de Casos', 1, ['ingreso']],
            [1, 1, 'Economía', 3, ['ingreso']],
            [1, 1, 'Contabilidad', 3, ['ingreso']],
            [1, 1, 'Administración', 3, ['ingreso']],
            
            // Año 1 - Trimestre II
            [1, 2, 'Estadística para la Gestión', 3, ['ingreso']],
            [1, 2, 'Entorno Económico', 3, ['ingreso']],
            [1, 2, 'Entorno Social Cultural', 3, ['ingreso']],
            [1, 2, 'Taller 2: Herramientas para el Trabajo de Grado: Métodos y Técnicas para la Investigación en Gestión', 1, ['ingreso']],
            
            // Año 1 - Trimestre III
            [1, 3, 'Aspectos Legales en Salud', 3, ['administracion']], // Requiere: Administración (curso 4)
            [1, 3, 'Desarrollo de Competencias Relacionales', 3, ['administracion']], // Requiere: Administración (curso 4)
            [1, 3, 'Dirección Estratégica de Sistemas de Salud', 3, ['administracion']], // Requiere: Administración (curso 4)
            [1, 3, 'Sistema de Salud y Gestión en Red', 3, ['entorno_economico']], // Requiere: Entorno Económico (curso 6)
            
            // Año 2 - Trimestre IV (trimestre 1 del año 2)
            [2, 4, 'Dirección Estratégica de Recursos Humanos', 3, ['administracion']], // Requiere: Administración
            [2, 4, 'Gestión de Operaciones, Logística y Calidad', 3, ['administracion']], // Requiere: Administración
            [2, 4, 'Epidemiología y Salud Pública para la Gestión', 3, ['entorno_economico']], // Requiere: Entorno Económico
            [2, 4, 'Trabajo de Grado I', 2, ['taller2']], // Requiere: Taller 2
            
            // Año 2 - Trimestre V (trimestre 2 del año 2)
            [2, 5, 'Calidad y Acreditación en Salud', 3, ['direccion_sistemas_salud']], // Requiere: Dirección Estratégica de Sistemas de Salud
            [2, 5, 'Formulación y Evaluación de Proyectos en Salud', 3, ['economia']], // Requiere: Economía
            [2, 5, 'Control Estratégico de Instituciones de Salud', 3, ['direccion_sistemas_salud']], // Requiere: Dirección Estratégica de Sistemas de Salud
            [2, 5, 'Trabajo de Grado II', 3, ['trabajo_grado_1']], // Requiere: Trabajo de Grado I
            
            // Año 2 - Trimestre VI (trimestre 3 del año 2)
            [2, 6, 'Electivo I', 3, ['ingreso']], // Requiere: Ingreso
            [2, 6, 'Electivo II', 3, ['ingreso']], // Requiere: Ingreso
            [2, 6, 'Taller 3: Desarrollo y Crecimiento Personal', 3, ['desarrollo_competencias']], // Requiere: Desarrollo de Competencias Relacionales
            [2, 6, 'Trabajo de Grado III', 3, ['trabajo_grado_2']], // Requiere: Trabajo de Grado II
        ];

        $cursosCreados = [];
        $mapaCursos = []; // Para mapear nombres de cursos a IDs
        $anioIngreso = 2024; // Año de ingreso para TODOS los cursos (2024)
        
        // Crear TODOS los cursos para año de ingreso 2024
        foreach ($cursosPorPeriodo as [$anio, $trimestre, $nombreCurso, $sct, $requisitos]) {
            $periodo = Period::where('magister_id', $magister->id)
                ->where('anio_ingreso', $anioIngreso)
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
                    [
                        'sct' => $sct,
                        'requisitos' => null, // Se actualizará después
                    ]
                );
                
                // Guardar en el mapa para referencias posteriores
                $mapaCursos[$nombreCurso] = $curso->id;
                $cursosCreados[] = $curso;
            }
        }
        
        // Ahora crear SOLO los primeros 4 cursos para año de ingreso 2025 (solo trimestre 1)
        $this->command->info('   📖 Creando primeros 4 cursos para Ingreso 2025 (solo Trimestre 1)...');
        
        $primeros4Cursos = [
            [1, 1, 'Taller 1 – Habilidades de Aprendizaje: Presentación Efectiva, Trabajo en Equipo, Metodología de Casos', 1, ['ingreso']],
            [1, 1, 'Economía', 3, ['ingreso']],
            [1, 1, 'Contabilidad', 3, ['ingreso']],
            [1, 1, 'Administración', 3, ['ingreso']],
        ];
        
        $cursos2025 = [];
        $anioIngreso2025 = 2025;
        
        foreach ($primeros4Cursos as [$anio, $trimestre, $nombreCurso, $sct, $requisitos]) {
            $periodo = Period::where('magister_id', $magister->id)
                ->where('anio_ingreso', $anioIngreso2025)
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
                    [
                        'sct' => $sct,
                        'requisitos' => null, // Se actualizará después
                    ]
                );
                
                $cursos2025[] = $curso;
            }
        }
        
        $this->command->info('   ✅ 4 cursos creados para Ingreso 2025 (Trimestre 1)');
        
        // Ahora actualizar los requisitos con los IDs reales para cursos 2024
        foreach ($cursosCreados as $curso) {
            $requisitosOriginales = null;
            
            // Buscar los requisitos originales del array
            foreach ($cursosPorPeriodo as [$anio, $trimestre, $nombreCurso, $sct, $requisitos]) {
                if ($curso->nombre === $nombreCurso && is_array($requisitos)) {
                    $requisitosOriginales = $requisitos;
                    break;
                }
            }
            
            if ($requisitosOriginales) {
                $requisitosIDs = [];
                foreach ($requisitosOriginales as $req) {
                    if ($req === 'ingreso') {
                        $requisitosIDs[] = 'ingreso';
                    } elseif ($req === 'administracion') {
                        // Buscar el ID del curso "Administración"
                        $adminCurso = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Administración');
                        if ($adminCurso) {
                            $requisitosIDs[] = $adminCurso->id;
                        }
                    } elseif ($req === 'entorno_economico') {
                        // Buscar el ID del curso "Entorno Económico"
                        $entornoCurso = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Entorno Económico');
                        if ($entornoCurso) {
                            $requisitosIDs[] = $entornoCurso->id;
                        }
                    } elseif ($req === 'taller2') {
                        // Buscar el ID del curso "Taller 2: Herramientas para el Trabajo de Grado..."
                        $taller2Curso = collect($cursosCreados)->first(fn($c) => str_contains($c->nombre, 'Taller 2'));
                        if ($taller2Curso) {
                            $requisitosIDs[] = $taller2Curso->id;
                        }
                    } elseif ($req === 'direccion_sistemas_salud') {
                        // Buscar el ID del curso "Dirección Estratégica de Sistemas de Salud"
                        $dssCurso = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Dirección Estratégica de Sistemas de Salud');
                        if ($dssCurso) {
                            $requisitosIDs[] = $dssCurso->id;
                        }
                    } elseif ($req === 'economia') {
                        // Buscar el ID del curso "Economía"
                        $economiaCurso = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Economía');
                        if ($economiaCurso) {
                            $requisitosIDs[] = $economiaCurso->id;
                        }
                    } elseif ($req === 'trabajo_grado_1') {
                        // Buscar el ID del curso "Trabajo de Grado I"
                        $tg1Curso = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Trabajo de Grado I');
                        if ($tg1Curso) {
                            $requisitosIDs[] = $tg1Curso->id;
                        }
                    } elseif ($req === 'desarrollo_competencias') {
                        // Buscar el ID del curso "Desarrollo de Competencias Relacionales"
                        $competenciasCurso = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Desarrollo de Competencias Relacionales');
                        if ($competenciasCurso) {
                            $requisitosIDs[] = $competenciasCurso->id;
                        }
                    } elseif ($req === 'trabajo_grado_2') {
                        // Buscar el ID del curso "Trabajo de Grado II"
                        $tg2Curso = collect($cursosCreados)->first(fn($c) => $c->nombre === 'Trabajo de Grado II');
                        if ($tg2Curso) {
                            $requisitosIDs[] = $tg2Curso->id;
                        }
                    }
                }
                
                $curso->update(['requisitos' => implode(',', $requisitosIDs)]);
            }
        }
        
        // Ahora actualizar los requisitos para los cursos de 2025
        foreach ($cursos2025 as $curso) {
            $requisitosOriginales = null;
            
            // Buscar los requisitos originales del array
            foreach ($primeros4Cursos as [$anio, $trimestre, $nombreCurso, $sct, $requisitos]) {
                if ($curso->nombre === $nombreCurso && is_array($requisitos)) {
                    $requisitosOriginales = $requisitos;
                    break;
                }
            }
            
            if ($requisitosOriginales) {
                $requisitosIDs = [];
                foreach ($requisitosOriginales as $req) {
                    if ($req === 'ingreso') {
                        $requisitosIDs[] = 'ingreso';
                    }
                    // Los 4 primeros cursos solo tienen requisito de ingreso
                }
                
                $curso->update(['requisitos' => implode(',', $requisitosIDs)]);
            }
        }

        $this->command->info('   ✅ ' . count($cursosCreados) . ' cursos creados para Ingreso 2024 (todos los trimestres del Año 1 y 2)');
        $this->command->info('   ✅ Total: ' . (count($cursosCreados) + count($cursos2025)) . ' cursos creados en total');

        // ==========================================
        // 5. SALAS
        // ==========================================
        $this->command->info('🏛️ Creando Salas...');
        
        $salaFEN1 = Room::firstOrCreate(
            ['name' => 'Sala FEN 1'],
            [
                'location' => 'Facultad de Economía y Negocios',
                'capacity' => 40,
                'description' => '',
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

        $salaFEN2 = Room::firstOrCreate(
            ['name' => 'Sala FEN 2'],
            [
                'location' => 'Facultad de Economía y Negocios',
                'capacity' => 35,
                'description' => '',
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
            ]
        );

        $salaFEN3 = Room::firstOrCreate(
            ['name' => 'Sala FEN 3'],
            [
                'location' => 'Facultad de Economía y Negocios',
                'capacity' => 30,
                'description' => '',
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

        $sala119 = Room::firstOrCreate(
            ['name' => 'Sala 119'],
                        [
                'location' => 'Facultad de Economía y Negocios',
                'capacity' => 20,
                'description' => '',
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

        $this->command->info('   ✅ 4 salas creadas (FEN 1, FEN 2, FEN 3, 119)');

        // ==========================================
        // 6. CLASES Y SESIONES
        // ==========================================
        $this->command->info('🗓️ Creando Clases y Sesiones para Ingreso 2025...');
        
        $zoomUrl = 'https://reuna.zoom.us/j/82980173545';
        $zoomInfo = 'ID: 829 8017 3545 | Código: 712683';

        // Obtener el período del 1er trimestre y los cursos correspondientes (año de ingreso 2025)
        $anioIngreso = 2025; // Año de ingreso para las clases
        $periodo1erTrimestre = Period::where('magister_id', $magister->id)
            ->where('anio_ingreso', $anioIngreso)
            ->where('anio', 1)
            ->where('numero', 1)
            ->first();
        
        // Buscar los cursos del 1er trimestre por nombre (de los cursos 2025)
        $cursoTaller1 = collect($cursos2025)->first(fn($c) => str_contains($c->nombre, 'Taller 1'));
        $cursoEconomia = collect($cursos2025)->first(fn($c) => $c->nombre === 'Economía');
        $cursoContabilidad = collect($cursos2025)->first(fn($c) => $c->nombre === 'Contabilidad');
        $cursoAdministracion = collect($cursos2025)->first(fn($c) => $c->nombre === 'Administración');
        
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
        // 7. STAFF
        // ==========================================
        $this->command->info('👔 Creando Staff...');
        
        $staffData = [
            // Autoridades
            [
                'nombre' => 'Arcadio Cerda',
                'cargo' => 'Decano Facultad de Economía y Negocios',
                'telefono' => '+56 712200300',
                'anexo' => '',
                'email' => 'acerda@utalca.cl'
            ],
            [
                'nombre' => 'José Leonardo Castillo',
                'cargo' => 'Director Administrativo',
                'telefono' => '+56 712417313',
                'anexo' => '',
                'email' => 'jcastillo@utalca.cl'
            ],
            
            // Directores de Magíster
            [
                'nombre' => 'Pablo Neudörfer, PhD',
                'cargo' => 'Director Magíster en Economía',
                'telefono' => '+56 712200350',
                'anexo' => '',
                'email' => 'pneudorfer@utalca.cl'
            ],
            [
                'nombre' => 'Dr. Jorge Navarrete',
                'cargo' => 'Director Magíster en Gestión y Políticas Públicas',
                'telefono' => '+56 712200351',
                'anexo' => '',
                'email' => 'jnavarrete@utalca.cl'
            ],
            [
                'nombre' => 'Luis Canales',
                'cargo' => 'Director Magíster en Gestión de Sistemas de Salud',
                'telefono' => '+56 712200352',
                'anexo' => '',
                'email' => 'lcanales@utalca.cl'
            ],
            
            // Coordinadoras/Asistentes de Programas
            [
                'nombre' => 'July Basoalto Riveros',
                'cargo' => 'Coordinadora Magíster en Economía',
                'telefono' => '+56 712200312',
                'anexo' => '',
                'email' => 'jbasoalto@utalca.cl'
            ],
            [
                'nombre' => 'Mary Isabel Sepúlveda G.',
                'cargo' => 'Coordinadora Magíster en Gestión y Políticas Públicas',
                'telefono' => '+56 712200313',
                'anexo' => '',
                'email' => 'msepulveda@utalca.cl'
            ],
            
            // Oficina de Postgrado
            [
                'nombre' => 'María Castillo',
                'cargo' => 'Asistente de Postgrado',
                'telefono' => '+56 712200314',
                'anexo' => '',
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
            
            // Personal de Apoyo
            [
                'nombre' => 'Cristian Barrientos',
                'cargo' => 'Auxiliar de Facultad, Campus Talca',
                'telefono' => '+56 9 76543210',
                'anexo' => null,
                'email' => 'cristian.barrientos@utalca.cl'
            ],
            [
                'nombre' => 'Miguel Suárez',
                'cargo' => 'Informático FEN, Campus Talca',
                'telefono' => '+56 712201789',
                'anexo' => '1789',
                'email' => 'msuarez@utalca.cl'
            ],
        ];

        foreach ($staffData as $staff) {
            Staff::firstOrCreate(
                ['email' => $staff['email']],
                $staff
            );
        }

        $this->command->info('   ✅ ' . count($staffData) . ' miembros del equipo creados (incluyendo 2 de personal de apoyo)');

        // ==========================================
        // 8. NOVEDADES
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


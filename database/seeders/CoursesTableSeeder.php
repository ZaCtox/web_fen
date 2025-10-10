<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Period;
use App\Models\MallaCurricular;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        // ========================================
        // CURSOS DEL PERIODO ACTUAL 2025-2026
        // ========================================
        $cursosActuales = [
            'Economía' => [
                [1, 1, "Matemáticas Avanzadas para Economía"],
                [1, 1, "Microeconomía Avanzada I"],
                [1, 1, "Macroeconomía Avanzada I"],
                [1, 2, "Econometría I"],
                [1, 2, "Microeconomía Avanzada II"],
                [1, 2, "Macroeconomía Avanzada II"],
                [1, 3, "Econometría II"],
                [1, 3, "Microeconomía Avanzada III"],
                [1, 3, "Metodología de Investigación en Economía"],
                [2, 4, "Electivo I"],
                [2, 4, "Electivo II"],
                [2, 4, "Proyecto de Tesis"],
                [2, 5, "Tesis"],
                [2, 6, "Tesis"],
            ],
            'Dirección y Planificación Tributaria' => [
                [1, 1, "Taller 1 - Habilidades de Aprendizaje: Presentación Efectiva, Trabajo en Equipo, Metodología de Casos"],
                [1, 1, "Lo Contencioso Tributario"],
                [1, 1, "Contabilidad y Tributación"],
                [1, 1, "Administración"],
                [1, 2, "Aspectos legales de la Empresa"],
                [1, 2, "Entorno Económico"],
                [1, 2, "Impuesto al Valor Agregado Aplicado"],
                [1, 2, "Finanzas Públicas"],
                [1, 3, "Impuestos Directos en Empresas"],
                [1, 3, "Fondo de Utilidades Tributarias"],
                [1, 3, "Tributación de Rentas del Trabajo"],
                [1, 3, "Ética y Tributación"],
                [2, 4, "Impuesto Global Complementario"],
                [2, 4, "Administración Financiera"],
                [2, 4, "Empresa y su Compromiso Tributario"],
                [2, 4, "Electivo I"],
                [2, 4, "Trabajo de Grado I"],
                [2, 5, "Tributación Internacional"],
                [2, 5, "Mercado de Capitales y su Tributación"],
                [2, 5, "Electivo II"],
                [2, 5, "Franquicias Tributarias"],
                [2, 5, "Trabajo de Grado II"],
                [2, 6, "Planificación Tributaria"],
                [2, 6, "Administración Tributaria Comparada"],
                [2, 6, "Finanzas Internacionales"],
                [2, 6, "Trabajo de Grado III"],
            ],
            'Gestión de Sistemas de Salud' => [
                [1, 1, "Taller 1 - Habilidad e Aprendizaje: Presentación efectiva, Trabajo en Equipo, Metodología de Casos"],
                [1, 1, "Economía"],
                [1, 1, "Contabilidad"],
                [1, 1, "Administración"],
                [1, 2, "Estadística para la gestión"],
                [1, 2, "Entorno Económico"],
                [1, 2, "Entorno Socio Cultural"],
                [1, 2, "Taller 2: Herramientas para el trabajo de grado: Métodos y técnicas para la investigación en gestión"],
                [1, 3, "Aspectos Legales en Salud"],
                [1, 3, "Desarrollo de Competencia Relaciónales"],
                [1, 3, "Dirección Estratégica en Sistemas de Salud"],
                [1, 3, "Sistema de Salud y Gestión en Red"],
                [2, 4, "Dirección Estratégica de Recursos Humanos"],
                [2, 4, "Gestión de Operaciones, Logística y Calidad"],
                [2, 4, "Epidemiología y Salud Pública para la Gestión"],
                [2, 4, "Trabajo de Grado I"],
                [2, 5, "Calidad y Acreditación en Salud"],
                [2, 5, "Formulación y Evaluación de Proyectos en Salud"],
                [2, 5, "Control Estratégico de Instituciones de Salud"],
                [2, 5, "Trabajo de Grado II"],
                [2, 6, "Electivo I"],
                [2, 6, "Electivo II"],
                [2, 6, "Taller 3: Desarrollo y crecimiento personal"],
                [2, 6, "Trabajo de Grado III"],
            ],
            'Gestión y Políticas Públicas' => [
                [1, 1, "Taller 1 - Habilidades de Aprendizaje: Presentación efectiva, Trabajo en equipo, Metodología de Casos"],
                [1, 1, "Economía"],
                [1, 1, "Contabilidad"],
                [1, 1, "Administración"],
                [1, 2, "Estadística para la Gestión"],
                [1, 2, "Entorno Económico"],
                [1, 2, "Entorno Socio Cultural"],
                [1, 2, "Taller 2 - Herramientas para el Trabajo Grado: Métodos y Técnicas para la Investigación en Gestión"],
                [1, 3, "Control de Gestión"],
                [1, 3, "Introducción a la Ciencia Política y Entorno Político"],
                [1, 3, "Entorno Normativo Institucional del Sector Público"],
                [1, 3, "Entorno Territorial"],
                [2, 4, "Cambio Organizacional y Gestión de Personas"],
                [2, 4, "Dirección Estratégica"],
                [2, 4, "Regulación y Políticas Públicas I"],
                [2, 4, "Trabajo de Grado I"],
                [2, 4, "Evaluación de Programas y Proyectos Públicos"],
                [2, 5, "Finanzas Públicas"],
                [2, 5, "Gestión Pública I"],
                [2, 5, "Políticas Públicas II"],
                [2, 5, "Contabilidad IFRS"],
                [2, 5, "Trabajo de Grado II"],
                [2, 5, "Gestión Pública II"],
                [2, 6, "Gestión Pública III"],
                [2, 6, "Trabajo de Grado III"],
            ]
        ];

        // ========================================
        // CURSOS DEL PERIODO PASADO 2024-2025
        // (Cursos al azar, menos cantidad)
        // ========================================
        $cursosPasados = [
            'Economía' => [
                [1, 1, "Matemáticas Avanzadas para Economía"],
                [1, 2, "Econometría I"],
                [1, 3, "Microeconomía Avanzada III"],
                [2, 4, "Proyecto de Tesis"],
                [2, 5, "Tesis"],
            ],
            'Dirección y Planificación Tributaria' => [
                [1, 1, "Contabilidad y Tributación"],
                [1, 2, "Finanzas Públicas"],
                [1, 3, "Impuestos Directos en Empresas"],
                [2, 4, "Administración Financiera"],
                [2, 5, "Tributación Internacional"],
                [2, 6, "Planificación Tributaria"],
            ],
            'Gestión de Sistemas de Salud' => [
                [1, 1, "Economía"],
                [1, 1, "Contabilidad"],
                [1, 2, "Estadística para la gestión"],
                [1, 3, "Aspectos Legales en Salud"],
                [2, 4, "Gestión de Operaciones, Logística y Calidad"],
                [2, 5, "Calidad y Acreditación en Salud"],
            ],
            'Gestión y Políticas Públicas' => [
                [1, 1, "Administración"],
                [1, 2, "Estadística para la Gestión"],
                [1, 3, "Control de Gestión"],
                [2, 4, "Dirección Estratégica"],
                [2, 5, "Finanzas Públicas"],
                [2, 6, "Gestión Pública III"],
            ]
        ];

        // Cachear periodos por cohorte
        $periodos2024_2025 = Period::where('cohorte', '2024-2025')
            ->get()
            ->keyBy(function($p) {
                return $p->anio . '-' . $p->numero;
            });

        $periodos2025_2026 = Period::where('cohorte', '2025-2026')
            ->get()
            ->keyBy(function($p) {
                return $p->anio . '-' . $p->numero;
            });

        $this->command->info('📚 Creando cursos del periodo ACTUAL (cohorte 2025-2026)...');
        
        foreach ($cursosActuales as $magisterNombre => $cursos) {
            $magister = Magister::where('nombre', $magisterNombre)->first();

            if (!$magister) {
                throw new \Exception("❌ Magíster no encontrado: $magisterNombre");
            }

            // Obtener la malla actual del magíster (2025-2026 vigente)
            $mallaActual = MallaCurricular::where('magister_id', $magister->id)
                ->where('codigo', 'LIKE', '%-2025-%')
                ->first();

            foreach ($cursos as [$anio, $trimestre, $nombreCurso]) {
                $key = $anio . '-' . $trimestre;
                $period = $periodos2025_2026->get($key);

                if (!$period) {
                    $this->command->warn("⚠️ Periodo no encontrado para año $anio, trimestre $trimestre (cohorte 2025-2026)");
                    continue;
                }

                Course::create([
                    'nombre' => $nombreCurso,
                    'magister_id' => $magister->id,
                    'period_id' => $period->id,
                    'malla_curricular_id' => $mallaActual?->id,
                ]);
            }
        }

        $this->command->info('📜 Creando cursos del periodo PASADO (cohorte 2024-2025)...');
        
        foreach ($cursosPasados as $magisterNombre => $cursos) {
            $magister = Magister::where('nombre', $magisterNombre)->first();

            if (!$magister) {
                continue;
            }

            // Obtener la malla del periodo pasado (2024-2025)
            $mallaPasada = MallaCurricular::where('magister_id', $magister->id)
                ->where('codigo', 'LIKE', '%-2024-%')
                ->first();

            foreach ($cursos as [$anio, $trimestre, $nombreCurso]) {
                $key = $anio . '-' . $trimestre;
                $period = $periodos2024_2025->get($key);

                if (!$period) {
                    continue;
                }

                Course::create([
                    'nombre' => $nombreCurso,
                    'magister_id' => $magister->id,
                    'period_id' => $period->id,
                    'malla_curricular_id' => $mallaPasada?->id,
                ]);
            }
        }

        $this->command->info('✅ Cursos creados correctamente para ambos periodos.');
    }
}



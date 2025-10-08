<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\Room;
use App\Models\Event;
use App\Models\User;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Emergency;
use App\Models\DailyReport;
use App\Models\Clase;
use App\Models\Novedad;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Estadísticas generales
        $stats = [
            'incidencias_pendientes' => Incident::where('estado', 'pendiente')->count(),
            'incidencias_totales' => Incident::count(),
            'emergencias_activas' => Emergency::where('active', true)->count(),
            'emergencias_totales' => Emergency::count(),
            'salas_totales' => Room::count(),
            'eventos_totales' => Event::count(),
            'usuarios_totales' => User::count(),
            'cursos_totales' => Course::count(),
            'magisters_totales' => Magister::count(),
        ];

        // Actividad reciente
        $actividadReciente = [
            'incidencias' => Incident::with('user')
                ->latest()
                ->take(5)
                ->get(),
            'emergencias' => Emergency::latest()
                ->take(3)
                ->get(),
            'reportes' => DailyReport::with('user')
                ->latest('report_date')
                ->take(3)
                ->get(),
        ];

        // Próximas clases (solo para docentes)
        $proximasClases = null;
        if ($user->rol === 'docente') {
            $proximasClases = Clase::with(['room', 'course'])
                ->where('fecha', '>=', now())
                ->orderBy('fecha')
                ->orderBy('hora_inicio')
                ->take(5)
                ->get();
        }

        // Accesos rápidos según el rol
        $accesosRapidos = $this->getAccesosRapidos($user->rol);

        // Novedades personalizadas por rol
        $novedades = $this->getNovedadesParaUsuario($user);

        return view('dashboard', compact('stats', 'actividadReciente', 'proximasClases', 'accesosRapidos', 'novedades', 'user'));
    }

    /**
     * Obtener accesos rápidos personalizados según el rol del usuario
     */
    private function getAccesosRapidos($rol)
    {
        $accesos = [
            'administrador' => [
                ['titulo' => 'Gestionar Usuarios', 'descripcion' => 'Crear, editar y eliminar usuarios', 'icono' => '👥', 'ruta' => 'usuarios.index', 'color' => 'blue'],
                ['titulo' => 'Ver Incidencias', 'descripcion' => 'Revisar y gestionar incidencias', 'icono' => '📋', 'ruta' => 'incidencias.index', 'color' => 'yellow'],
                ['titulo' => 'Gestionar Salas', 'descripcion' => 'Administrar salas y espacios', 'icono' => '🏫', 'ruta' => 'rooms.index', 'color' => 'purple'],
                ['titulo' => 'Ver Emergencias', 'descripcion' => 'Monitorear emergencias activas', 'icono' => '🚨', 'ruta' => 'emergencies.index', 'color' => 'red'],
                ['titulo' => 'Calendario', 'descripcion' => 'Ver calendario académico', 'icono' => '📅', 'ruta' => 'calendario', 'color' => 'green'],
                ['titulo' => 'Reportes Diarios', 'descripcion' => 'Ver reportes del personal', 'icono' => '📝', 'ruta' => 'daily-reports.index', 'color' => 'indigo'],
            ],
            'director_administrativo' => [
                ['titulo' => 'Ver Incidencias', 'descripcion' => 'Revisar incidencias reportadas', 'icono' => '📋', 'ruta' => 'incidencias.index', 'color' => 'yellow'],
                ['titulo' => 'Ver Salas', 'descripcion' => 'Consultar estado de salas', 'icono' => '🏫', 'ruta' => 'rooms.index', 'color' => 'purple'],
                ['titulo' => 'Ver Emergencias', 'descripcion' => 'Monitorear emergencias', 'icono' => '🚨', 'ruta' => 'emergencies.index', 'color' => 'red'],
                ['titulo' => 'Nuestro Equipo', 'descripcion' => 'Ver información del staff', 'icono' => '👨‍💼', 'ruta' => 'staff.index', 'color' => 'blue'],
                ['titulo' => 'Calendario', 'descripcion' => 'Ver calendario académico', 'icono' => '📅', 'ruta' => 'calendario', 'color' => 'green'],
                ['titulo' => 'Reportes Diarios', 'descripcion' => 'Ver reportes del personal', 'icono' => '📝', 'ruta' => 'daily-reports.index', 'color' => 'indigo'],
            ],
            'docente' => [
                ['titulo' => 'Mis Clases', 'descripcion' => 'Ver y gestionar mis clases', 'icono' => '📚', 'ruta' => 'clases.index', 'color' => 'blue'],
                ['titulo' => 'Reportar Incidencia', 'descripcion' => 'Reportar un problema', 'icono' => '⚠️', 'ruta' => 'incidencias.create', 'color' => 'yellow'],
                ['titulo' => 'Ver Salas', 'descripcion' => 'Consultar disponibilidad', 'icono' => '🏫', 'ruta' => 'rooms.index', 'color' => 'purple'],
                ['titulo' => 'Calendario', 'descripcion' => 'Ver calendario académico', 'icono' => '📅', 'ruta' => 'calendario', 'color' => 'green'],
            ],
            'administrativo' => [
                ['titulo' => 'Ver Incidencias', 'descripcion' => 'Revisar incidencias reportadas', 'icono' => '📋', 'ruta' => 'incidencias.index', 'color' => 'yellow'],
                ['titulo' => 'Ver Salas', 'descripcion' => 'Consultar estado de salas', 'icono' => '🏫', 'ruta' => 'rooms.index', 'color' => 'purple'],
                ['titulo' => 'Calendario', 'descripcion' => 'Ver calendario académico', 'icono' => '📅', 'ruta' => 'calendario', 'color' => 'green'],
                ['titulo' => 'Nuestro Equipo', 'descripcion' => 'Ver información del staff', 'icono' => '👨‍💼', 'ruta' => 'staff.index', 'color' => 'blue'],
            ],
            'asistente_postgrado' => [
                ['titulo' => 'Crear Reporte Diario', 'descripcion' => 'Registrar observaciones del día', 'icono' => '📝', 'ruta' => 'daily-reports.create', 'color' => 'indigo'],
                ['titulo' => 'Mis Reportes', 'descripcion' => 'Ver reportes creados', 'icono' => '📋', 'ruta' => 'daily-reports.index', 'color' => 'blue'],
                ['titulo' => 'Reportar Incidencia', 'descripcion' => 'Reportar un problema', 'icono' => '⚠️', 'ruta' => 'incidencias.create', 'color' => 'yellow'],
                ['titulo' => 'Ver Salas', 'descripcion' => 'Consultar estado de salas', 'icono' => '🏫', 'ruta' => 'rooms.index', 'color' => 'purple'],
            ],
        ];

        return $accesos[$rol] ?? [];
    }

    /**
     * Obtener novedades personalizadas según el rol del usuario
     */
    private function getNovedadesParaUsuario($user)
    {
        // Novedades manuales de la base de datos
        $novedadesManuales = Novedad::paraRol($user->rol)
            ->activas()
            ->porTipo('manual')
            ->orderBy('es_urgente', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        // Novedades automáticas basadas en estadísticas del sistema
        $novedadesAutomaticas = $this->generarNovedadesAutomaticas($user);

        // Combinar y limitar a 4 novedades máximo
        return $novedadesManuales->concat($novedadesAutomaticas)->take(4);
    }

    /**
     * Generar novedades automáticas basadas en el rol y estadísticas
     */
    private function generarNovedadesAutomaticas($user)
    {
        $novedades = collect();

        // Novedades específicas por rol
        switch ($user->rol) {
            case 'asistente_postgrado':
                // Recordatorio de incidencias pendientes para asistente (todas las del sistema)
                $incidenciasPendientes = Incident::where('estado', 'pendiente')->count();
                if ($incidenciasPendientes > 0) {
                    $novedades->push((object)[
                        'titulo' => 'Incidencias pendientes',
                        'contenido' => "Hay {$incidenciasPendientes} incidencias pendientes en el sistema que requieren atención.",
                        'tipo_novedad' => 'automatica',
                        'color' => 'yellow',
                        'icono' => 'warning',
                        'es_urgente' => $incidenciasPendientes > 5,
                        'acciones' => [
                            ['texto' => 'Ver incidencias', 'url' => route('incidencias.index'), 'color' => 'yellow']
                        ]
                    ]);
                }

                // Recordatorio de reportes diarios
                $ultimoReporte = DailyReport::where('user_id', $user->id)
                    ->latest('report_date')
                    ->first();
                
                if (!$ultimoReporte || $ultimoReporte->report_date->lt(now()->subDays(1))) {
                    $novedades->push((object)[
                        'titulo' => 'Reporte diario pendiente',
                        'contenido' => 'Recuerda crear tu reporte diario para mantener actualizada la información.',
                        'tipo_novedad' => 'automatica',
                        'color' => 'blue',
                        'icono' => 'info',
                        'es_urgente' => true,
                        'acciones' => [
                            ['texto' => 'Crear reporte', 'url' => route('daily-reports.create'), 'color' => 'blue']
                        ]
                    ]);
                }
                break;

            case 'administrador':
                // Estadísticas del sistema para admin
                $incidenciasResueltas = Incident::where('estado', 'resuelta')
                    ->whereMonth('updated_at', now()->month)
                    ->count();
                
                if ($incidenciasResueltas > 0) {
                    $novedades->push((object)[
                        'titulo' => 'Resumen del mes',
                        'contenido' => "Se han resuelto {$incidenciasResueltas} incidencias este mes. ¡Excelente trabajo del equipo!",
                        'tipo_novedad' => 'automatica',
                        'color' => 'green',
                        'icono' => 'check',
                        'es_urgente' => false,
                        'acciones' => [
                            ['texto' => 'Ver estadísticas', 'url' => route('incidencias.estadisticas'), 'color' => 'green']
                        ]
                    ]);
                }

                // Incidencias pendientes que requieren atención
                $incidenciasPendientes = Incident::where('estado', 'pendiente')->count();
                if ($incidenciasPendientes > 0) {
                    $novedades->push((object)[
                        'titulo' => 'Incidencias pendientes',
                        'contenido' => "Hay {$incidenciasPendientes} incidencias pendientes en el sistema que requieren atención.",
                        'tipo_novedad' => 'automatica',
                        'color' => 'yellow',
                        'icono' => 'warning',
                        'es_urgente' => $incidenciasPendientes > 10,
                        'acciones' => [
                            ['texto' => 'Ver incidencias', 'url' => route('incidencias.index'), 'color' => 'yellow']
                        ]
                    ]);
                }
                break;

            case 'director_administrativo':
                // Incidencias pendientes para director administrativo
                $incidenciasPendientes = Incident::where('estado', 'pendiente')->count();
                if ($incidenciasPendientes > 0) {
                    $novedades->push((object)[
                        'titulo' => 'Incidencias pendientes',
                        'contenido' => "Hay {$incidenciasPendientes} incidencias pendientes que requieren supervisión.",
                        'tipo_novedad' => 'automatica',
                        'color' => 'yellow',
                        'icono' => 'warning',
                        'es_urgente' => $incidenciasPendientes > 8,
                        'acciones' => [
                            ['texto' => 'Ver incidencias', 'url' => route('incidencias.index'), 'color' => 'yellow']
                        ]
                    ]);
                }
                break;

            case 'técnico':
            case 'auxiliar':
                // Incidencias en revisión para técnicos y auxiliares
                $incidenciasEnRevision = Incident::where('estado', 'en_revision')->count();
                if ($incidenciasEnRevision > 0) {
                    $novedades->push((object)[
                        'titulo' => 'Incidencias en revisión',
                        'contenido' => "Hay {$incidenciasEnRevision} incidencias en revisión que requieren tu atención técnica.",
                        'tipo_novedad' => 'automatica',
                        'color' => 'blue',
                        'icono' => 'info',
                        'es_urgente' => $incidenciasEnRevision > 3,
                        'acciones' => [
                            ['texto' => 'Ver incidencias', 'url' => route('incidencias.index'), 'color' => 'blue']
                        ]
                    ]);
                }
                break;

            case 'docente':
                // Recordatorio de clases para docentes
                $clasesHoy = Clase::where('fecha', now()->toDateString())
                    ->count();
                
                if ($clasesHoy > 0) {
                    $novedades->push((object)[
                        'titulo' => 'Clases programadas hoy',
                        'contenido' => "Tienes {$clasesHoy} clase(s) programada(s) para hoy.",
                        'tipo_novedad' => 'automatica',
                        'color' => 'blue',
                        'icono' => 'calendar',
                        'es_urgente' => false,
                        'acciones' => [
                            ['texto' => 'Ver mis clases', 'url' => route('clases.index'), 'color' => 'blue']
                        ]
                    ]);
                }

                // Estado de sus propias incidencias
                $misIncidenciasPendientes = Incident::where('user_id', $user->id)
                    ->where('estado', 'pendiente')
                    ->count();
                
                if ($misIncidenciasPendientes > 0) {
                    $novedades->push((object)[
                        'titulo' => 'Mis incidencias pendientes',
                        'contenido' => "Tienes {$misIncidenciasPendientes} incidencia(s) pendiente(s) de resolución.",
                        'tipo_novedad' => 'automatica',
                        'color' => 'yellow',
                        'icono' => 'warning',
                        'es_urgente' => $misIncidenciasPendientes > 2,
                        'acciones' => [
                            ['texto' => 'Ver mis incidencias', 'url' => route('incidencias.index'), 'color' => 'yellow']
                        ]
                    ]);
                }
                break;

            case 'director_programa':
                // Estado de sus propias incidencias
                $misIncidenciasPendientes = Incident::where('user_id', $user->id)
                    ->where('estado', 'pendiente')
                    ->count();
                
                if ($misIncidenciasPendientes > 0) {
                    $novedades->push((object)[
                        'titulo' => 'Mis incidencias pendientes',
                        'contenido' => "Tienes {$misIncidenciasPendientes} incidencia(s) pendiente(s) de resolución.",
                        'tipo_novedad' => 'automatica',
                        'color' => 'yellow',
                        'icono' => 'warning',
                        'es_urgente' => $misIncidenciasPendientes > 2,
                        'acciones' => [
                            ['texto' => 'Ver mis incidencias', 'url' => route('incidencias.index'), 'color' => 'yellow']
                        ]
                    ]);
                }
                break;

            case 'administrativo':
                // Estado de sus propias incidencias
                $misIncidenciasPendientes = Incident::where('user_id', $user->id)
                    ->where('estado', 'pendiente')
                    ->count();
                
                if ($misIncidenciasPendientes > 0) {
                    $novedades->push((object)[
                        'titulo' => 'Mis incidencias pendientes',
                        'contenido' => "Tienes {$misIncidenciasPendientes} incidencia(s) pendiente(s) de resolución.",
                        'tipo_novedad' => 'automatica',
                        'color' => 'yellow',
                        'icono' => 'warning',
                        'es_urgente' => $misIncidenciasPendientes > 2,
                        'acciones' => [
                            ['texto' => 'Ver mis incidencias', 'url' => route('incidencias.index'), 'color' => 'yellow']
                        ]
                    ]);
                }
                break;
        }

        // Novedades generales para todos los roles
        $emergenciasActivas = Emergency::where('active', true)->count();
        if ($emergenciasActivas > 0) {
            $novedades->push((object)[
                'titulo' => 'Emergencia activa',
                'contenido' => "Hay {$emergenciasActivas} emergencia(s) activa(s) en el sistema.",
                'tipo_novedad' => 'automatica',
                'color' => 'red',
                'icono' => 'alert',
                'es_urgente' => true,
                'acciones' => [
                    ['texto' => 'Ver emergencias', 'url' => route('emergencies.index'), 'color' => 'red']
                ]
            ]);
        }

        // Notificación para roles que pueden resolver incidencias sobre incidencias muy antiguas
        $rolesQuePuedenResolver = ['administrador', 'director_administrativo', 'técnico', 'auxiliar', 'asistente_postgrado'];
        if (in_array($user->rol, $rolesQuePuedenResolver)) {
            $incidenciasAntiguas = Incident::where('estado', 'pendiente')
                ->where('created_at', '<', now()->subDays(7))
                ->count();
            
            if ($incidenciasAntiguas > 0) {
                $novedades->push((object)[
                    'titulo' => 'Incidencias antiguas',
                    'contenido' => "Hay {$incidenciasAntiguas} incidencia(s) pendiente(s) por más de 7 días que requieren atención inmediata.",
                    'tipo_novedad' => 'automatica',
                    'color' => 'red',
                    'icono' => 'alert',
                    'es_urgente' => true,
                    'acciones' => [
                        ['texto' => 'Ver incidencias', 'url' => route('incidencias.index'), 'color' => 'red']
                    ]
                ]);
            }
        }

        return $novedades;
    }
}

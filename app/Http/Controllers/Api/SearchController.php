<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Staff;
use App\Models\Period;
use App\Models\Incident;
use App\Models\Emergency;
use App\Models\Clase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Búsqueda global en toda la plataforma
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'status' => 'success',
                'results' => [],
                'meta' => [
                    'query' => $query,
                    'total' => 0
                ]
            ]);
        }

        $results = [];
        $user = Auth::user();

        // Solo usuarios autenticados pueden buscar
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'No autenticado'
            ], 401);
        }

        $isAdmin = $user->rol === 'administrador';
        $canViewContent = in_array($user->rol, ['administrador', 'docente', 'administrativo']);

        // Buscar en Salas
        if ($canViewContent) {
            $rooms = Room::where('name', 'like', "%{$query}%")
                ->orWhere('location', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'location', 'capacity']);

            foreach ($rooms as $room) {
                $results[] = [
                    'type' => 'room',
                    'id' => $room->id,
                    'title' => $room->name,
                    'description' => $room->location . ' • Cap. ' . $room->capacity,
                    'url' => route('rooms.show', $room->id),
                    'badge' => null
                ];
            }
        }

        // Buscar en Cursos
        if ($canViewContent) {
            $courses = Course::with('magister')
                ->where('nombre', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'nombre', 'magister_id']);

            foreach ($courses as $course) {
                $results[] = [
                    'type' => 'course',
                    'id' => $course->id,
                    'title' => $course->nombre,
                    'description' => $course->magister ? $course->magister->nombre : 'Sin programa',
                    'url' => route('courses.edit', $course->id),
                    'badge' => null
                ];
            }
        }

        // Buscar en Magisters
        if ($canViewContent) {
            $magisters = Magister::where('nombre', 'like', "%{$query}%")
                ->orWhere('encargado', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'nombre', 'encargado']);

            foreach ($magisters as $magister) {
                $results[] = [
                    'type' => 'magister',
                    'id' => $magister->id,
                    'title' => $magister->nombre,
                    'description' => $magister->encargado ? 'Encargado: ' . $magister->encargado : null,
                    'url' => route('magisters.edit', $magister->id),
                    'badge' => null
                ];
            }
        }

        // Buscar en Staff
        if ($canViewContent) {
            $staff = Staff::where('nombre', 'like', "%{$query}%")
                ->orWhere('cargo', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'nombre', 'cargo', 'email']);

            foreach ($staff as $member) {
                $results[] = [
                    'type' => 'staff',
                    'id' => $member->id,
                    'title' => $member->nombre,
                    'description' => $member->cargo . ' • ' . $member->email,
                    'url' => route('staff.show', $member->id),
                    'badge' => null
                ];
            }
        }

        // Buscar en Períodos
        if ($canViewContent) {
            $periods = Period::where('anio', 'like', "%{$query}%")
                ->orWhere('numero', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'anio', 'numero', 'fecha_inicio', 'fecha_fin']);

            foreach ($periods as $period) {
                $results[] = [
                    'type' => 'period',
                    'id' => $period->id,
                    'title' => "Período {$period->anio} - Trimestre {$period->numero}",
                    'description' => $period->fecha_inicio->format('d/m/Y') . ' - ' . $period->fecha_fin->format('d/m/Y'),
                    'url' => route('periods.edit', $period->id),
                    'badge' => null
                ];
            }
        }

        // Buscar en Incidencias
        if ($canViewContent) {
            $incidents = Incident::with('room')
                ->where('titulo', 'like', "%{$query}%")
                ->orWhere('descripcion', 'like', "%{$query}%")
                ->orWhere('nro_ticket', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'titulo', 'estado', 'room_id', 'nro_ticket']);

            foreach ($incidents as $incident) {
                $badge = null;
                if ($incident->estado === 'pendiente') {
                    $badge = 'Pendiente';
                } elseif ($incident->estado === 'no_resuelta') {
                    $badge = 'No Resuelta';
                }

                $results[] = [
                    'type' => 'incident',
                    'id' => $incident->id,
                    'title' => $incident->titulo,
                    'description' => ($incident->room ? $incident->room->name : 'Sin sala') . 
                                   ($incident->nro_ticket ? ' • #' . $incident->nro_ticket : ''),
                    'url' => route('incidencias.show', $incident->id),
                    'badge' => $badge
                ];
            }
        }

        // Buscar en Emergencias
        if ($isAdmin) {
            $emergencies = Emergency::where('title', 'like', "%{$query}%")
                ->orWhere('message', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'title', 'active', 'expires_at']);

            foreach ($emergencies as $emergency) {
                $isActive = $emergency->active && (!$emergency->expires_at || $emergency->expires_at->isFuture());
                
                $results[] = [
                    'type' => 'emergency',
                    'id' => $emergency->id,
                    'title' => $emergency->title,
                    'description' => $isActive ? 'Emergencia Activa' : 'Emergencia Inactiva',
                    'url' => route('emergencies.edit', $emergency->id),
                    'badge' => $isActive ? 'Activa' : null
                ];
            }
        }

        // Buscar en Clases
        if ($canViewContent) {
            $clases = Clase::with(['course', 'room'])
                ->whereHas('course', function($q) use ($query) {
                    $q->where('nombre', 'like', "%{$query}%");
                })
                ->orWhereHas('room', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->orWhere('dia', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'course_id', 'room_id', 'dia', 'hora_inicio', 'hora_fin']);

            foreach ($clases as $clase) {
                $results[] = [
                    'type' => 'clase',
                    'id' => $clase->id,
                    'title' => $clase->course ? $clase->course->nombre : 'Clase',
                    'description' => ($clase->dia ?? 'Sin día') . ' ' . 
                                   (substr($clase->hora_inicio ?? '', 0, 5)) . '-' . 
                                   (substr($clase->hora_fin ?? '', 0, 5)) . 
                                   ($clase->room ? ' • ' . $clase->room->name : ''),
                    'url' => route('clases.edit', $clase->id),
                    'badge' => null
                ];
            }
        }

        // Buscar en Usuarios (solo admin)
        if ($isAdmin) {
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('rol', 'like', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'email', 'rol']);

            foreach ($users as $usuario) {
                $results[] = [
                    'type' => 'user',
                    'id' => $usuario->id,
                    'title' => $usuario->name,
                    'description' => $usuario->email . ' • ' . ucfirst($usuario->rol),
                    'url' => route('usuarios.edit', $usuario->id),
                    'badge' => null
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'results' => $results,
            'meta' => [
                'query' => $query,
                'total' => count($results)
            ]
        ]);
    }
}


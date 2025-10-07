<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Incident;
use App\Models\Notification;

if (!function_exists('tieneRol')) {
    function tieneRol(array|string $roles): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        $roles = is_array($roles) ? $roles : [$roles];

        return in_array($user->rol, $roles);
    }
}

if (!function_exists('incidenciasPendientes')) {
    function incidenciasPendientes(): int
    {
        // Contar incidencias que no están resueltas ni no resueltas
        return Incident::whereNotIn('estado', ['resuelta', 'no_resuelta'])->count();
    }
}

if (!function_exists('notificacionesNoLeidas')) {
    function notificacionesNoLeidas(): int
    {
        $user = Auth::user();
        if (!$user) return 0;
        
        // Contar notificaciones no leídas del usuario actual
        return Notification::forUser($user->id)->unread()->count();
    }
}

if (!function_exists('crearNotificacionCambioEstado')) {
    function crearNotificacionCambioEstado($incidentId, $estadoAnterior, $estadoNuevo, $usuarioActual)
    {
        $incident = Incident::find($incidentId);
        if (!$incident || !$incident->user) return;
        
        // Solo notificar al creador de la incidencia si no es el mismo que está cambiando el estado
        if ($incident->user_id === $usuarioActual->id) return;
        
        $titulos = [
            'pendiente' => 'Pendiente',
            'en_revision' => 'En Revisión',
            'resuelta' => 'Resuelta',
            'no_resuelta' => 'No Resuelta'
        ];
        
        $titulo = "Estado de incidencia actualizado";
        $mensaje = "Tu incidencia '{$incident->titulo}' cambió de '{$titulos[$estadoAnterior]}' a '{$titulos[$estadoNuevo]}'";
        
        Notification::create([
            'user_id' => $incident->user_id,
            'incident_id' => $incidentId,
            'type' => 'status_change',
            'title' => $titulo,
            'message' => $mensaje,
            'read' => false,
        ]);
    }
}

if (!function_exists('obtenerNotificacionesUsuario')) {
    function obtenerNotificacionesUsuario($limit = 5)
    {
        $user = Auth::user();
        if (!$user) return collect();
        
        return Notification::forUser($user->id)
            ->with('incident')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

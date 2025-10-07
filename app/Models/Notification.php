<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'incident_id',
        'type',
        'title',
        'message',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la incidencia
    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    // Scope para notificaciones no leídas
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    // Scope para notificaciones de un usuario específico
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
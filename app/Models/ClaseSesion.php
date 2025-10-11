<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClaseSesion extends Model
{
    use HasFactory;

    protected $table = 'clase_sesiones';

    protected $fillable = [
        'clase_id',
        'fecha',
        'dia',
        'hora_inicio',
        'hora_fin',
        'modalidad',
        'room_id',
        'url_zoom',
        'url_grabacion',
        'estado',
        'observaciones',
        'numero_sesion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Relación con la clase (módulo)
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    /**
     * Relación con la sala
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Scope para sesiones pasadas
     */
    public function scopePasadas($query)
    {
        return $query->where('fecha', '<', now()->toDateString());
    }

    /**
     * Scope para sesiones futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('fecha', '>=', now()->toDateString());
    }

    /**
     * Scope para sesiones completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Verificar si la sesión es pasada
     */
    public function getEsPasadaAttribute()
    {
        return $this->fecha < now()->toDateString();
    }

    /**
     * Verificar si la sesión es hoy
     */
    public function getEsHoyAttribute()
    {
        return $this->fecha->isToday();
    }

    /**
     * Verificar si tiene grabación
     */
    public function getTieneGrabacionAttribute()
    {
        return !empty($this->url_grabacion);
    }

    /**
     * Obtener badge de estado
     */
    public function getEstadoBadgeAttribute()
    {
        return match($this->estado) {
            'completada' => '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Completada</span>',
            'cancelada' => '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Cancelada</span>',
            default => '<span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">Pendiente</span>',
        };
    }
}


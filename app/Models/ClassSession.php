<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClassSession extends Model
{
    use HasFactory;

    protected $table = 'class_sessions';

    protected $fillable = [
        'class_id',
        'date',
        'day',
        'start_time',
        'end_time',
        'modality',
        'room_id',
        'zoom_url',
        'recording_url',
        'status',
        'observations',
        'session_number',
        'time_blocks',
        'break_times',
        'coffee_break_start',
        'coffee_break_end',
        'lunch_break_start',
        'lunch_break_end',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'coffee_break_start' => 'datetime:H:i',
        'coffee_break_end' => 'datetime:H:i',
        'lunch_break_start' => 'datetime:H:i',
        'lunch_break_end' => 'datetime:H:i',
        'time_blocks' => 'array',
        'break_times' => 'array',
    ];

    public function class()
    {
        return $this->belongsTo(Class::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Scope para sesiones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('status', 'activa');
    }

    /**
     * Scope para sesiones por fecha
     */
    public function scopePorFecha($query, $fecha)
    {
        return $query->where('date', $fecha);
    }

    /**
     * Scope para sesiones por modalidad
     */
    public function scopePorModalidad($query, $modalidad)
    {
        return $query->where('modality', $modalidad);
    }

    /**
     * Scope para sesiones futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }

    /**
     * Scope para sesiones pasadas
     */
    public function scopePasadas($query)
    {
        return $query->where('date', '<', now()->toDateString());
    }

    /**
     * Scope para sesiones de hoy
     */
    public function scopeHoy($query)
    {
        return $query->where('date', now()->toDateString());
    }

    /**
     * Accessor para verificar si la sesión es hoy
     */
    public function getIsTodayAttribute()
    {
        return $this->date->isToday();
    }

    /**
     * Accessor para verificar si la sesión es futura
     */
    public function getIsFutureAttribute()
    {
        return $this->date->isFuture();
    }

    /**
     * Accessor para verificar si la sesión es pasada
     */
    public function getIsPastAttribute()
    {
        return $this->date->isPast();
    }

    /**
     * Accessor para la duración de la sesión
     */
    public function getDurationAttribute()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        return $start->diffInMinutes($end);
    }

    /**
     * Accessor para la duración en horas
     */
    public function getDurationInHoursAttribute()
    {
        return round($this->duration / 60, 2);
    }

    /**
     * Accessor para el estado CSS
     */
    public function getStatusClassAttribute()
    {
        $classes = [
            'activa' => 'bg-green-100 text-green-800',
            'cancelada' => 'bg-red-100 text-red-800',
            'completada' => 'bg-blue-100 text-blue-800',
            'reprogramada' => 'bg-yellow-100 text-yellow-800',
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Accessor para el icono de modalidad
     */
    public function getModalityIconAttribute()
    {
        $icons = [
            'presencial' => '🏢',
            'online' => '💻',
            'hibrida' => '🔄',
        ];

        return $icons[$this->modality] ?? '❓';
    }

    /**
     * Accessor para el nombre completo de la sesión
     */
    public function getFullNameAttribute()
    {
        return $this->class->course->name . ' - Sesión ' . $this->session_number;
    }

    /**
     * Accessor para el tiempo transcurrido desde la sesión
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Accessor para verificar si tiene grabación
     */
    public function getHasRecordingAttribute()
    {
        return !empty($this->recording_url);
    }

    /**
     * Accessor para verificar si tiene URL de Zoom
     */
    public function getHasZoomUrlAttribute()
    {
        return !empty($this->zoom_url);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_report_id',
        'location_type',
        'room_id',
        'location_detail',
        'observation',
        'photo_url',
        'order',
        'hora',
        'escala',
        'programa',
        'area',
        'tarea',
    ];

    // Relación con el reporte diario
    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }

    // Relación con la sala (si aplica)
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Accessor para obtener la ubicación completa
    public function getUbicacionCompletaAttribute()
    {
        if ($this->location_type === 'Sala' && $this->room) {
            return $this->room->name;
        } elseif ($this->location_detail) {
            return $this->location_detail;
        }
        return $this->location_type;
    }

    // Accessor para obtener el tipo de ubicación formateado
    public function getTipoUbicacionAttribute()
    {
        $tipos = [
            'Sala' => 'Sala de Clases',
            'Baño' => 'Baño',
            'Pasillo' => 'Pasillo',
            'Laboratorio' => 'Laboratorio',
            'Oficina' => 'Oficina',
            'Otro' => 'Otro'
        ];

        return $tipos[$this->location_type] ?? $this->location_type;
    }

    // Accessor para verificar si tiene foto
    public function getTieneFotoAttribute()
    {
        return !empty($this->photo_url);
    }

    // Accessor para obtener la observación truncada
    public function getObservacionCortaAttribute($length = 100)
    {
        return strlen($this->observation) > $length 
            ? substr($this->observation, 0, $length) . '...' 
            : $this->observation;
    }

    // Scope para entradas con foto
    public function scopeConFoto($query)
    {
        return $query->whereNotNull('photo_url');
    }

    // Scope para entradas por tipo de ubicación
    public function scopePorTipoUbicacion($query, $tipo)
    {
        return $query->where('location_type', $tipo);
    }

    // Accessor para obtener el nivel de severidad
    public function getNivelSeveridadAttribute()
    {
        if (!$this->escala) return null;
        
        if ($this->escala <= 2) return 'Normal';
        if ($this->escala <= 4) return 'Leve';
        if ($this->escala <= 6) return 'Moderado';
        if ($this->escala <= 8) return 'Fuerte';
        return 'Crítico';
    }

    // Accessor para obtener el color de la escala
    public function getColorEscalaAttribute()
    {
        if (!$this->escala) return '#6B7280';
        
        if ($this->escala == 1) return '#4DBCC6';
        if ($this->escala == 2) return '#3C9EAA';
        if ($this->escala == 3) return '#8B8232';
        if ($this->escala == 4) return '#B4A53C';
        if ($this->escala == 5) return '#FFCC00';
        if ($this->escala == 6) return '#FF9900';
        if ($this->escala == 7) return '#FF6600';
        if ($this->escala == 8) return '#FF3300';
        if ($this->escala == 9) return '#FF0000';
        return '#CC0000';
    }

    // Accessor para obtener el emoji de la escala
    public function getEmojiEscalaAttribute()
    {
        if (!$this->escala) return '😐';
        
        if ($this->escala <= 2) return '😊';
        if ($this->escala <= 4) return '🙂';
        if ($this->escala <= 6) return '😐';
        if ($this->escala <= 8) return '😟';
        return '😡';
    }
}
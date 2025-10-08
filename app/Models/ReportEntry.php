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
}
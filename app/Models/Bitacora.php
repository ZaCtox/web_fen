<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'lugar',
        'room_id',
        'detalle_ubicacion',
        'descripcion',
        'foto_url',
        'pdf_path',
        'user_id', // Para saber quién creó el reporte
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relación con User (quien creó el reporte)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor para obtener la ubicación completa
    public function getUbicacionCompletaAttribute()
    {
        if ($this->lugar === 'Sala' && $this->room) {
            return $this->room->name;
        } elseif ($this->detalle_ubicacion) {
            return $this->detalle_ubicacion;
        }
        return $this->lugar;
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

        return $tipos[$this->lugar] ?? $this->lugar;
    }

    // Accessor para verificar si tiene foto
    public function getTieneFotoAttribute()
    {
        return !empty($this->foto_url);
    }

    // Accessor para verificar si tiene PDF
    public function getTienePdfAttribute()
    {
        return !empty($this->pdf_path);
    }

    // Scope para filtrar por tipo de ubicación
    public function scopePorUbicacion($query, $ubicacion)
    {
        return $query->where('lugar', $ubicacion);
    }

    // Scope para filtrar por sala específica
    public function scopePorSala($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    // Scope para reportes con foto
    public function scopeConFoto($query)
    {
        return $query->whereNotNull('foto_url');
    }

    // Scope para reportes con PDF
    public function scopeConPdf($query)
    {
        return $query->whereNotNull('pdf_path');
    }

    // Scope para reportes recientes (últimos 30 días)
    public function scopeRecientes($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays(30));
    }

    // Método para obtener la descripción truncada
    public function getDescripcionCortaAttribute($length = 100)
    {
        return strlen($this->descripcion) > $length 
            ? substr($this->descripcion, 0, $length) . '...' 
            : $this->descripcion;
    }

    // Método para obtener la fecha formateada
    public function getFechaFormateadaAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    // Método para obtener la fecha relativa
    public function getFechaRelativaAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}

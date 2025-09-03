<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    // ✅ Campos que se pueden guardar en bloque con create() o update()
    protected $fillable = [
        'titulo',
        'descripcion',
        'room_id',
        'estado',
        'imagen',
        'comentario',
        'public_id',
        'user_id',
        'nro_ticket',
        'resuelta_en',
        'resolved_by',
    ];

    protected $casts = [
        'resuelta_en' => 'datetime',
    ];


    // ✅ Relación con el usuario que la creó
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function logs()
    {
        return $this->hasMany(IncidentLog::class);
    }

    public function images()
    {
        return $this->hasMany(IncidentImage::class);
    }
}

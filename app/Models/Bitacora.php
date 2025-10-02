<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable = [
        'lugar',
        'room_id',
        'detalle_ubicacion',
        'descripcion',
        'foto_url',
        'pdf_path',
    ];

    // Relación con Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

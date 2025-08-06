<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'description',
        'calefaccion',
        'energia_electrica',
        'existe_aseo',
        'plumones',
        'borrador',
        'pizarra_limpia',
        'computador_funcional',
        'cables_computador',
        'control_remoto_camara',
        'televisor_funcional',
    ];

    // app/Models/Room.php
    public function usages()
    {
        return $this->hasMany(RoomUsage::class);
    }

    public function clases()
    {
        return $this->hasMany(Clase::class);
    }


}


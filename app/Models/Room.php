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

    public function clases()
    {
        return $this->hasMany(Clase::class);
    }

    public function roomAssignments()
    {
        return $this->hasMany(RoomAssignment::class);
    }

    public function academicEvents()
    {
        return $this->hasMany(AcademicEvent::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}


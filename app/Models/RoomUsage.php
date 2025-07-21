<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'trimestre_id',
        'subject',
        'magister',
        'dia',
        'hora_inicio',
        'hora_fin',
        'room_id',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }

}

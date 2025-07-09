<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'trimestre',
        'subject',
        'dia',
        'horario',
        'magister',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

}

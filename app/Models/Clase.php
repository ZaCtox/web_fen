<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;


    protected $fillable = [
        'course_id',
        'period_id',
        'room_id',
        'modality',
        'dia',
        'hora_inicio',
        'hora_fin',
        'url_zoom'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'course_id',
        'dia',
        'hora_inicio',
        'hora_fin',
        'room_id',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

        public function course()
    {
        return $this->belongsTo(Course::class);
    }

}

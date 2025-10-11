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
        'url_zoom',
        'encargado',
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

    /**
     * Relación con las sesiones de esta clase
     */
    public function sesiones()
    {
        return $this->hasMany(ClaseSesion::class)->orderBy('fecha', 'asc');
    }

    // app/Models/Clase.php
    public function scopeFiltrar($q, array $f)
    {
        return $q
            ->when($f['magister'] ?? null, fn($q, $v) =>
                $q->whereHas('course.magister', fn($q2) => $q2->where('nombre', $v)))
            ->when($f['sala'] ?? null, fn($q, $v) =>
                $q->whereHas('room', fn($q2) => $q2->where('name', $v)))
            ->when($f['dia'] ?? null, fn($q, $v) =>
                $q->whereHas('sesiones', fn($q2) => $q2->where('dia', $v)));
    }
}

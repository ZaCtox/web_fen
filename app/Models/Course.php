<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'magister_id', 'malla_curricular_id', 'period_id'];


    public function magister()
    {
        return $this->belongsTo(Magister::class);
    }

    public function mallaCurricular()
    {
        return $this->belongsTo(MallaCurricular::class, 'malla_curricular_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function clases()
    {
        return $this->hasMany(Clase::class, 'course_id');
    }

    // Scope para filtrar por malla curricular
    public function scopeDeMalla($query, $mallaId)
    {
        return $query->where('malla_curricular_id', $mallaId);
    }

}


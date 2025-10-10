<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magister extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'color', 'orden', 'encargado', 'asistente', 'telefono','anexo', 'correo'];

    public function courses()
    {
        return $this->hasMany(Course::class)
            ->with('period')
            ->orderBy('period_id');
    }

    public function mallasCurriculares()
    {
        return $this->hasMany(MallaCurricular::class)->orderBy('año_inicio', 'desc');
    }

    public function mallasActivas()
    {
        return $this->hasMany(MallaCurricular::class)->where('activa', true)->orderBy('año_inicio', 'desc');
    }

    public function novedades()
    {
        return $this->hasMany(Novedad::class);
    }

    public function informes()
    {
        return $this->hasMany(Informe::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

}

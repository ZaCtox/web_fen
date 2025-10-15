<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'magister_id',
        'numero',
        'anio',
        'anio_ingreso',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    protected $appends = ['nombre_completo'];


    // Accessor para nombre completo generado dinámicamente
    public function getNombreCompletoAttribute()
    {
        // Calcular el año académico basado en el número de trimestre
        // Trimestres 1-3 = Año 1, Trimestres 4-6 = Año 2
        $anioAcademico = ceil($this->numero / 3);
        
        return "Año {$anioAcademico}";
    }

    public function magister()
    {
        return $this->belongsTo(Magister::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Scopes útiles
    public function scopeDeCohorte($query, $cohorte)
    {
        // Mantener por compatibilidad, pero usar anio_ingreso
        return $query->where('anio_ingreso', $cohorte);
    }

    public function scopeDeAnioIngreso($query, $anioIngreso)
    {
        return $query->where('anio_ingreso', $anioIngreso);
    }

    public function scopeActual($query)
    {
        $fechaHoy = now();
        return $query->where('fecha_inicio', '<=', $fechaHoy)
                     ->where('fecha_fin', '>=', $fechaHoy);
    }

    public function scopePasados($query)
    {
        return $query->where('fecha_fin', '<', now());
    }

    public function scopeFuturos($query)
    {
        return $query->where('fecha_inicio', '>', now());
    }

}


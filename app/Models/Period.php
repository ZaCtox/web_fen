<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'anio',
        'fecha_inicio',
        'fecha_fin',
        'activo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    // Accessor para nombre completo generado dinámicamente
    public function getNombreCompletoAttribute()
    {
        $romanos = [1 => 'I', 2 => 'II', 3 => 'III'];
        return "Año {$this->anio} - Trimestre " . ($romanos[$this->numero] ?? $this->numero);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }


}

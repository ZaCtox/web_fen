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
        $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
        return "Año {$this->anio} - Trimestre " . ($romanos[$this->numero] ?? $this->numero);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }


}

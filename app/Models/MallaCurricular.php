<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MallaCurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'magister_id',
        'nombre',
        'codigo',
        'año_inicio',
        'año_fin',
        'activa',
        'descripcion'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'año_inicio' => 'integer',
        'año_fin' => 'integer',
    ];

    // Relación con el programa de magíster
    public function magister()
    {
        return $this->belongsTo(Magister::class);
    }

    // Relación con los cursos de esta malla
    public function courses()
    {
        return $this->hasMany(Course::class, 'malla_curricular_id')
                    ->with('period')
                    ->orderBy('period_id');
    }

    // Scopes útiles
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopeDelMagister($query, $magisterId)
    {
        return $query->where('magister_id', $magisterId);
    }

    public function scopeVigentes($query)
    {
        $añoActual = now()->year;
        return $query->where('activa', true)
                     ->where('año_inicio', '<=', $añoActual)
                     ->where(function($q) use ($añoActual) {
                         $q->whereNull('año_fin')
                           ->orWhere('año_fin', '>=', $añoActual);
                     });
    }

    // Atributos calculados
    public function getEsVigenteAttribute()
    {
        if (!$this->activa) return false;
        
        $añoActual = now()->year;
        $inicioOk = $this->año_inicio <= $añoActual;
        $finOk = is_null($this->año_fin) || $this->año_fin >= $añoActual;
        
        return $inicioOk && $finOk;
    }

    public function getPeriodoVigenciaAttribute()
    {
        $fin = $this->año_fin ? $this->año_fin : 'Actualidad';
        return "{$this->año_inicio} - {$fin}";
    }

    public function getCantidadCursosAttribute()
    {
        return $this->courses()->count();
    }

    public function getEstadoBadgeAttribute()
    {
        if ($this->activa && $this->es_vigente) {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Vigente</span>';
        } elseif ($this->activa) {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Programada</span>';
        } else {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">Inactiva</span>';
        }
    }
}

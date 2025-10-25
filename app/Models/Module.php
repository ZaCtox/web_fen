<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $table = 'modules';

    protected $fillable = [
        'name',
        'sct',
        'requirements',
        'program_id',
        'period_id',
        'type',
        'description',
        'objectives',
        'methodology',
        'evaluation',
        'bibliography',
    ];

    protected $casts = [
        'requirements' => 'array',
        'objectives' => 'array',
        'methodology' => 'array',
        'evaluation' => 'array',
        'bibliography' => 'array',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'module_id');
    }

    /**
     * Scope para módulos activos
     */
    public function scopeActivos($query)
    {
        return $query->whereHas('period', function($q) {
            $q->where('fecha_inicio', '<=', now())
              ->where('fecha_fin', '>=', now());
        });
    }

    /**
     * Scope para módulos por programa
     */
    public function scopePorPrograma($query, $programId)
    {
        return $query->where('program_id', $programId);
    }

    /**
     * Scope para módulos por período
     */
    public function scopePorPeriodo($query, $periodId)
    {
        return $query->where('period_id', $periodId);
    }

    /**
     * Scope para búsqueda
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('name', 'like', '%' . $termino . '%')
                    ->orWhere('description', 'like', '%' . $termino . '%');
    }

    /**
     * Scope para módulos por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('type', $tipo);
    }

    /**
     * Accessor para el nombre completo del módulo
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->sct . ' SCT)';
    }

    /**
     * Accessor para verificar si tiene clases
     */
    public function getHasClassesAttribute()
    {
        return $this->classes()->count() > 0;
    }

    /**
     * Accessor para el número de clases
     */
    public function getClassesCountAttribute()
    {
        return $this->classes()->count();
    }

    /**
     * Accessor para el color del programa
     */
    public function getProgramColorAttribute()
    {
        return $this->program->color ?? 'blue';
    }

    /**
     * Accessor para el nombre del programa
     */
    public function getProgramNameAttribute()
    {
        return $this->program->name ?? 'Sin programa';
    }

    /**
     * Accessor para verificar si es obligatorio
     */
    public function getIsRequiredAttribute()
    {
        return $this->type === 'obligatorio';
    }

    /**
     * Accessor para verificar si es electivo
     */
    public function getIsElectiveAttribute()
    {
        return $this->type === 'electivo';
    }

    /**
     * Accessor para el tipo en español
     */
    public function getTypeInSpanishAttribute()
    {
        $types = [
            'obligatorio' => 'Obligatorio',
            'electivo' => 'Electivo',
            'seminario' => 'Seminario',
            'taller' => 'Taller',
            'tesis' => 'Tesis',
        ];

        return $types[$this->type] ?? 'No especificado';
    }

    /**
     * Accessor para la duración estimada
     */
    public function getEstimatedDurationAttribute()
    {
        // Calcular duración basada en SCT (1 SCT = 1 hora semanal)
        return $this->sct * 16; // 16 semanas por semestre
    }

    /**
     * Accessor para el estado del módulo
     */
    public function getStatusAttribute()
    {
        if ($this->classes()->count() === 0) {
            return 'sin_clases';
        }

        $hasFutureClasses = $this->classes()
            ->where('date', '>=', now()->toDateString())
            ->exists();

        if ($hasFutureClasses) {
            return 'activo';
        }

        return 'completado';
    }

    /**
     * Accessor para el estado CSS
     */
    public function getStatusClassAttribute()
    {
        $classes = [
            'activo' => 'bg-green-100 text-green-800',
            'completado' => 'bg-blue-100 text-blue-800',
            'sin_clases' => 'bg-gray-100 text-gray-800',
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}

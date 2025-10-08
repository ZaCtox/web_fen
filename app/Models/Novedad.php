<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Novedad extends Model
{
    protected $table = 'novedades'; // ✅ Plural correcto
    
    protected $fillable = [
        'titulo',
        'contenido', 
        'tipo',
        'imagen',
        'user_id',
        'magister_id',
        'visible_publico',
        'roles_visibles',
        'tipo_novedad',
        'es_urgente',
        'fecha_expiracion',
        'icono',
        'color',
        'acciones'
    ];

    protected $casts = [
        'roles_visibles' => 'array',
        'acciones' => 'array',
        'es_urgente' => 'boolean',
        'visible_publico' => 'boolean',
        'fecha_expiracion' => 'datetime:Y-m-d H:i:s'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function magister()
    {
        return $this->belongsTo(Magister::class);
    }

    // Scopes
    public function scopeParaRol(Builder $query, string $rol)
    {
        return $query->where(function ($q) use ($rol) {
            $q->where('visible_publico', true)
              ->orWhereJsonContains('roles_visibles', $rol);
        });
    }

    public function scopeActivas(Builder $query)
    {
        return $query->where(function ($q) {
            $q->whereNull('fecha_expiracion')
              ->orWhere('fecha_expiracion', '>', now());
        });
    }

    public function scopePorTipo(Builder $query, string $tipo)
    {
        return $query->where('tipo_novedad', $tipo);
    }

    public function scopeUrgentes(Builder $query)
    {
        return $query->where('es_urgente', true);
    }

    // Mutators
    public function setFechaExpiracionAttribute($value)
    {
        if (empty($value) || $value === '0000-00-00 00:00:00') {
            $this->attributes['fecha_expiracion'] = null;
        } else {
            $this->attributes['fecha_expiracion'] = $value;
        }
    }

    // Métodos de utilidad
    public function esVisibleParaRol(string $rol): bool
    {
        if ($this->visible_publico) {
            return true;
        }

        return in_array($rol, $this->roles_visibles ?? []);
    }

    public function estaExpirada(): bool
    {
        return $this->fecha_expiracion && $this->fecha_expiracion instanceof \Carbon\Carbon && $this->fecha_expiracion->isPast();
    }

    // Métodos estáticos para crear novedades automáticas
    public static function crearAutomatica(string $titulo, string $contenido, array $roles = [], array $opciones = [])
    {
        return self::create([
            'titulo' => $titulo,
            'contenido' => $contenido,
            'tipo_novedad' => 'automatica',
            'roles_visibles' => $roles,
            'visible_publico' => empty($roles),
            'color' => $opciones['color'] ?? 'blue',
            'icono' => $opciones['icono'] ?? null,
            'es_urgente' => $opciones['urgente'] ?? false,
            'fecha_expiracion' => $opciones['expiracion'] ?? null,
            'acciones' => $opciones['acciones'] ?? null,
            'user_id' => auth()->id()
        ]);
    }
}

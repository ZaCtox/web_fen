<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'content',
        'announcement_type',
        'program_id',
        'user_id',
        'is_public',
        'is_urgent',
        'expiration_date',
        'icon',
        'color',
        'visible_roles',
        'image',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_urgent' => 'boolean',
        'expiration_date' => 'datetime',
        'visible_roles' => 'array',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para anuncios activos (no expirados)
     */
    public function scopeActivas($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expiration_date')
              ->orWhere('expiration_date', '>', now());
        });
    }

    /**
     * Scope para anuncios públicos
     */
    public function scopePublicos($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope para anuncios urgentes
     */
    public function scopeUrgentes($query)
    {
        return $query->where('is_urgent', true);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('announcement_type', $tipo);
    }

    /**
     * Scope para anuncios visibles para un rol específico
     */
    public function scopeVisiblesParaRol($query, $rol)
    {
        return $query->where(function($q) use ($rol) {
            $q->whereNull('visible_roles')
              ->orWhereJsonContains('visible_roles', $rol);
        });
    }

    /**
     * Scope para búsqueda
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('title', 'like', '%' . $termino . '%')
              ->orWhere('content', 'like', '%' . $termino . '%');
        });
    }

    /**
     * Accessor para verificar si el anuncio está expirado
     */
    public function getIsExpiredAttribute()
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    /**
     * Accessor para verificar si el anuncio está activo
     */
    public function getIsActiveAttribute()
    {
        return !$this->is_expired;
    }

    /**
     * Accessor para el tiempo transcurrido desde la creación
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Accessor para el contenido truncado
     */
    public function getExcerptAttribute()
    {
        return \Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Accessor para el color CSS
     */
    public function getColorClassAttribute()
    {
        $colors = [
            'blue' => 'bg-blue-100 text-blue-800',
            'red' => 'bg-red-100 text-red-800',
            'green' => 'bg-green-100 text-green-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
            'purple' => 'bg-purple-100 text-purple-800',
            'gray' => 'bg-gray-100 text-gray-800',
        ];

        return $colors[$this->color] ?? $colors['blue'];
    }
}

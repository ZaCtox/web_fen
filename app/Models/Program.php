<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = [
        'name',
        'color',
        'contact_name',
        'contact_email',
        'contact_phone',
        'assistant_name',
        'assistant_email',
        'assistant_phone',
        'order',
    ];

    public function modules()
    {
        return $this->hasMany(Module::class, 'program_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'program_id');
    }

    /**
     * Scope para programas activos
     */
    public function scopeActivos($query)
    {
        return $query->whereHas('modules');
    }

    /**
     * Scope para búsqueda por nombre
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('name', 'like', '%' . $termino . '%');
    }

    /**
     * Scope para ordenar por orden personalizado
     */
    public function scopeOrdenados($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Accessor para el color CSS
     */
    public function getColorClassAttribute()
    {
        $colors = [
            'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
            'red' => 'bg-red-100 text-red-800 border-red-200',
            'green' => 'bg-green-100 text-green-800 border-green-200',
            'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
            'gray' => 'bg-gray-100 text-gray-800 border-gray-200',
        ];

        return $colors[$this->color] ?? $colors['blue'];
    }

    /**
     * Accessor para el color de fondo
     */
    public function getBackgroundColorAttribute()
    {
        $colors = [
            'blue' => '#dbeafe',
            'red' => '#fee2e2',
            'green' => '#dcfce7',
            'yellow' => '#fef3c7',
            'purple' => '#e9d5ff',
            'gray' => '#f3f4f6',
        ];

        return $colors[$this->color] ?? $colors['blue'];
    }

    /**
     * Accessor para el color de texto
     */
    public function getTextColorAttribute()
    {
        $colors = [
            'blue' => '#1e40af',
            'red' => '#dc2626',
            'green' => '#16a34a',
            'yellow' => '#d97706',
            'purple' => '#7c3aed',
            'gray' => '#374151',
        ];

        return $colors[$this->color] ?? $colors['blue'];
    }

    /**
     * Accessor para el nombre corto (iniciales)
     */
    public function getShortNameAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return $initials;
    }

    /**
     * Accessor para verificar si tiene módulos
     */
    public function getHasModulesAttribute()
    {
        return $this->modules()->count() > 0;
    }

    /**
     * Accessor para el número de módulos
     */
    public function getModulesCountAttribute()
    {
        return $this->modules()->count();
    }

    /**
     * Accessor para el total de SCT
     */
    public function getTotalSctAttribute()
    {
        return $this->modules()->sum('sct');
    }
}

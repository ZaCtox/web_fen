<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'user_id',
        'page_type',
        'url',
        'method',
        'ip_address',
        'user_agent',
        'session_id',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    // Relación con usuario (puede ser null para visitantes anónimos)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes útiles para estadísticas
    public function scopeCalendarioPublico($query)
    {
        return $query->where('page_type', 'calendario_publico');
    }

    public function scopeCalendarioAutenticado($query)
    {
        return $query->where('page_type', 'calendario_autenticado');
    }

    public function scopeInicioPublico($query)
    {
        return $query->where('page_type', 'inicio_publico');
    }

    public function scopeDashboardAutenticado($query)
    {
        return $query->where('page_type', 'dashboard_autenticado');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visited_at', now()->month)
            ->whereYear('visited_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('visited_at', now()->year);
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('visited_at', [$start, $end]);
    }

    // Método para contar sesiones únicas
    public static function countUniqueSessions($pageType = null, $startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($pageType) {
            $query->where('page_type', $pageType);
        }
        
        if ($startDate && $endDate) {
            $query->betweenDates($startDate, $endDate);
        }
        
        return $query->distinct('session_id')->count('session_id');
    }
}


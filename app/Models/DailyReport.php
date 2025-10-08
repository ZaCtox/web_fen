<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'report_date',
        'summary',
        'user_id',
        'pdf_path',
    ];

    protected $casts = [
        'report_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con el usuario que creó el reporte
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con las entradas del reporte
    public function entries()
    {
        return $this->hasMany(ReportEntry::class)->orderBy('order');
    }

    // Accessor para obtener la fecha formateada
    public function getFechaFormateadaAttribute()
    {
        return $this->report_date->format('d/m/Y');
    }

    // Accessor para obtener la fecha relativa
    public function getFechaRelativaAttribute()
    {
        return $this->report_date->diffForHumans();
    }

    // Accessor para verificar si tiene PDF
    public function getTienePdfAttribute()
    {
        return !empty($this->pdf_path);
    }

    // Accessor para obtener el número de entradas
    public function getNumeroEntradasAttribute()
    {
        return $this->entries()->count();
    }

    // Scope para reportes de hoy
    public function scopeHoy($query)
    {
        return $query->whereDate('report_date', Carbon::today());
    }

    // Scope para reportes recientes
    public function scopeRecientes($query, $dias = 7)
    {
        return $query->where('report_date', '>=', Carbon::now()->subDays($dias));
    }

    // Método para generar título automático
    public static function generarTitulo($fecha = null)
    {
        $fecha = $fecha ? Carbon::parse($fecha) : Carbon::today();
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        $dia = $dias[$fecha->dayOfWeek];
        $mes = $meses[$fecha->month - 1];
        
        return "Reporte {$dia} {$fecha->day} de {$mes} {$fecha->year}";
    }
}
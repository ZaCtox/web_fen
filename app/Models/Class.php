<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'course_id',
        'period_id',
        'room_id',
        'modality',
        'day',
        'start_time',
        'end_time',
        'zoom_url',
        'date',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'date' => 'date',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relación con las sesiones de esta clase
     */
    public function sesiones()
    {
        return $this->hasMany(ClassSession::class)->orderBy('date', 'asc');
    }

    /**
     * Scope para filtrar por programa
     */
    public function scopeFiltrar($q, array $f)
    {
        return $q
            ->when($f['program'] ?? null, fn($q, $v) =>
                $q->whereHas('module.program', fn($q2) => $q2->where('name', $v)))
            ->when($f['period'] ?? null, fn($q, $v) =>
                $q->whereHas('period', fn($q2) => $q2->where('id', $v)))
            ->when($f['room'] ?? null, fn($q, $v) =>
                $q->where('room_id', $v))
            ->when($f['modality'] ?? null, fn($q, $v) =>
                $q->where('modality', $v))
            ->when($f['day'] ?? null, fn($q, $v) =>
                $q->where('day', $v))
            ->when($f['date_from'] ?? null, fn($q, $v) =>
                $q->where('date', '>=', $v))
            ->when($f['date_to'] ?? null, fn($q, $v) =>
                $q->where('date', '<=', $v));
    }

    /**
     * Scope para clases activas
     */
    public function scopeActivas($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }

    /**
     * Scope para clases por modalidad
     */
    public function scopePorModalidad($query, $modalidad)
    {
        return $query->where('modality', $modalidad);
    }

    /**
     * Scope para clases por día
     */
    public function scopePorDia($query, $dia)
    {
        return $query->where('day', $dia);
    }

    /**
     * Accessor para el nombre completo de la clase
     */
    public function getFullNameAttribute()
    {
        return $this->module->name . ' - ' . $this->day . ' ' . $this->start_time->format('H:i');
    }

    /**
     * Accessor para verificar si la clase es hoy
     */
    public function getIsTodayAttribute()
    {
        return $this->date->isToday();
    }

    /**
     * Accessor para verificar si la clase es futura
     */
    public function getIsFutureAttribute()
    {
        return $this->date->isFuture();
    }
}

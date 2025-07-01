<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    // ✅ Campos que se pueden guardar en bloque con create() o update()
    protected $fillable = [
        'titulo',
        'descripcion',
        'sala',
        'estado',
        'imagen',
        'user_id',
        'resuelta_en',
    ];

    protected $casts = [
        'resuelta_en' => 'datetime',
    ];


    // ✅ Relación con el usuario que la creó
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}

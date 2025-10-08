<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'tipo', 'mime', 'archivo', 'user_id', 'magister_id'];


    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function magister()
    {
        return $this->belongsTo(Magister::class);
    }
}

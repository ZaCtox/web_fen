<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trimestre extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'año', 'numero', 'fecha_inicio', 'fecha_fin'];

    public function usos()
    {
        return $this->hasMany(RoomUsage::class);
    }
}

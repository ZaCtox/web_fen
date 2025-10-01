<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magister extends Model
{
    use HasFactory;

<<<<<<< Updated upstream
    protected $fillable = ['nombre', 'color', 'encargado', 'telefono', 'correo'];

=======
<<<<<<< Updated upstream
    protected $fillable = ['nombre'];
=======
    protected $fillable = ['nombre', 'color',  'encargado', 'asistente', 'telefono','anexo', 'correo'];
>>>>>>> Stashed changes
>>>>>>> Stashed changes

    public function courses()
    {
        return $this->hasMany(Course::class)
            ->with('period')
            ->orderBy('period_id');
    }

}

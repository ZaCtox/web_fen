<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magister extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}

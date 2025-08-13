<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magister extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','color'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}

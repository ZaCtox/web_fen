<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'magister_id'];


    public function magister()
    {
        return $this->belongsTo(Magister::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }


}


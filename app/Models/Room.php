<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'description',
    ];

    // app/Models/Room.php
    public function usages()
    {
        return $this->hasMany(RoomUsage::class);
    }

}


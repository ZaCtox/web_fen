<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'magister_id',
        'start_time',
        'end_time',
        'room_id',
        'created_by',
        'type',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function magister()
    {
        return $this->belongsTo(Magister::class);
    }
}

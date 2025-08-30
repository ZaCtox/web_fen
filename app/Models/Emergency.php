<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'message', 'active', 'expires_at', 'created_by'];

    protected $casts = [
        'expires_at' => 'datetime', // ✅ Esto convierte automáticamente a Carbon
        'active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

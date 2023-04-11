<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory,Searchable;

    protected $casts = [
        'sim' => 'array'
    ];

    public function scopeConnected($query)
    {
        return $query->where('status', 1);
    }
    public function scopeDisConnected($query)
    {
        return $query->where('status', 0);
    }
}

<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory,Searchable;

    public function scopeActive($scope)
    {
        return $scope->where('status', 1);
    }
    public function scopeInactive($scope)
    {
        return $scope->where('status', 0);
    }
}

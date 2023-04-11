<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory, Searchable;


    public function scopeList($query)
    {
        return $query->whereIn('status', [0, 1]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
    public function contact()
    {
        return $this->belongsToMany(Contact::class, 'group_contacts', 'group_id');
    }
}

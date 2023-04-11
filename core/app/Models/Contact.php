<?php

namespace App\Models;

use App\Traits\FileExport;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{

    use FileExport, Searchable, HasFactory;

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function scopeBanned($query)
    {
        return $query->where('status', 0);
    }

    public function groupContact()
    {
        return $this->belongsToMany(Group::class, 'group_contacts', 'contact_id');
    }
}

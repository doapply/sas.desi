<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    public function sms()
    {
        return $this->hasMany(Sms::class, 'batch_id');
    }
}

<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sms extends Model
{
    use HasFactory, Searchable;

    protected $guarded = ['id'];

    protected $casts = [
        'device_sim_slot' => 'array'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
    public function failReason()
    {
        return $this->belongsTo(SmsFailedErrorCode::class,'error_code','code');
    }
}

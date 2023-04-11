<?php

namespace App\Models;


use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    use HasApiTokens;

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function activeApiKey()
    {
        return $this->hasOne(ApiKey::class, 'admin_id')->where('status', 1);
    }

    public function apiKey()
    {
        return $this->hasMany(ApiKey::class, 'admin_id');
    }

    public function createToken(string $name, $deviceId, array $abilities = ['*'])
    {
        $token = $this->tokens()->create([
            'name'      => $name,
            'token'     => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
        ]);
        $token->device_id = $deviceId;
        $token->save();

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }
}

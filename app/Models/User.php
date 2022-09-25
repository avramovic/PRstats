<?php

namespace PRStats\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use URL;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function getLoginLink()
    {
        return url(URL::signedRoute('login', ['id' => $this->id], now()->addMinutes(30), false));
    }
}

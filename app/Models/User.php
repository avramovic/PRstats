<?php

namespace PRStats\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use URL;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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

    public function canEdit(Player $player)
    {
        return $player->user_id == $this->id || $this->is_admin;
    }

    public function canEditUser(User $user)
    {
        return $user->id == $this->id || $this->is_admin;
    }

    public function getAvatarUrl($size = 140)
    {
        $default = vsprintf('https://robohash.org/%s.png?set=set5&size=%dx%d', [
            md5(strtolower($this->email)),
            $size,
            $size,
        ]);

        return "https://www.gravatar.com/avatar/".md5(strtolower(trim($this->email)))."?d=".urlencode($default)."&s=".$size;
    }

    public function getLink()
    {
        return route('user', [
            'id'   => $this->id,
            'slug' => Str::slug($this->name),
        ]);
    }

}

<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}

<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{

    protected $guarded = ['id'];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)->withTimestamps()->withPivot(['score', 'kills', 'deaths']);
    }
}

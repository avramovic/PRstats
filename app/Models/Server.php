<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    //

    public function getLink()
    {
        $slug = str_slug($this->name);
        return route('server', [$this->id, $slug]);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}

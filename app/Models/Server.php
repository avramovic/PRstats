<?php

namespace PRStats\Models;

use Carbon\Carbon;
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

    public function wasSeenRecently($mins = 5)
    {
        return $this->updated_at->diffInMinutes() < $mins;
    }
}

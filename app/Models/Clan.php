<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    //

    public function getLink()
    {
        $slug = empty($this->slug) ? 'clan' : $this->slug;
        return route('clan', [$this->id, $slug]);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function getLeaderAttribute()
    {
        return $this->players->sortByDesc('total_score')->first();
    }
}

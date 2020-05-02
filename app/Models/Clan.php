<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;
use PRStats\Models\Traits\FormatScoreTrait;

class Clan extends Model
{
    use FormatScoreTrait;

    public function getLink()
    {
        $slug = empty($this->slug) ? 'clan' : $this->slug;
        return route('clan', [$this->id, $slug]);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function matches()
    {
        return $this->hasManyThrough(Match::class, Player::class);
    }

    public function getLeaderAttribute()
    {
        return $this->players->sortByDesc('total_score')->first();
    }

    public function getLastPlayerSeenAttribute()
    {
        return $this->players->sortByDesc('updated_at')->first();
    }
}

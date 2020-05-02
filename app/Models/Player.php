<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;
use PRStats\Models\Traits\FormatScoreTrait;
use PRStats\Models\Traits\WasSeenRecentlyTrait;

class Player extends Model
{
    use WasSeenRecentlyTrait, FormatScoreTrait;

    public function getLink()
    {
        $slug = empty($this->slug) ? 'player' : $this->slug;
        return route('player', [$this->pid, $slug]);
    }

    public function clan()
    {
        return $this->belongsTo(Clan::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function getClanNameAttribute()
    {
        return $this->clan->name;
    }

    public function getFullNameAttribute()
    {
        if (empty($this->clan_id)) {
            return $this->name;
        }

        return $this->getClanNameAttribute().' '.$this->name;
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class)->withTimestamps()->withPivot(['score', 'kills', 'deaths', 'team']);
    }

}

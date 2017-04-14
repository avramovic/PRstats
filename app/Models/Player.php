<?php

namespace PRStats\Models;

use PRStats\Models\Traits\WasSeenRecentlyTrait;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use WasSeenRecentlyTrait;

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

}

<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;
use PRStats\Models\Traits\FormatScoreTrait;
use PRStats\Models\Traits\HasCountryFlag;
use PRStats\Models\Traits\WasSeenRecentlyTrait;

class Player extends Model
{
    use WasSeenRecentlyTrait, FormatScoreTrait, HasCountryFlag;

    protected $guarded = ['id'];

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

    public function getAvatarUrl($size=140)
    {
        return vsprintf('https://robohash.org/%s.png?set=set5&size=%dx%d', [
            md5($this->name),
            $size,
            $size,
        ]);
    }

    public function minutesPlayed()
    {
        return $this->matches->reduce(function ($carry, $entry) {
            return $carry + $entry->pivot->updated_at->diffInMinutes($entry->pivot->created_at);
        }, 0);
    }

}

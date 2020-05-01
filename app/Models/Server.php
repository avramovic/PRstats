<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;
use PRStats\Models\Traits\FormatScoreTrait;
use PRStats\Models\Traits\WasSeenRecentlyTrait;

class Server extends Model
{
    use WasSeenRecentlyTrait, FormatScoreTrait;

    public function getLink()
    {
        $slug = str_slug($this->name);
        return route('server', [$this->id, $slug]);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function getMapImageName()
    {
        return strtolower(str_replace(' ', '', $this->last_map));
    }

    public function getLastMapImageUrl($image = 'tile')
    {
        return '//www.realitymod.com/mapgallery/images/maps/'.$this->getMapImageName().'/'.$image.'.jpg';
    }

    public function matches()
    {
        return $this->hasMany(Match::class);
    }
}

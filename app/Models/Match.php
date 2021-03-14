<?php

namespace PRStats\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use PRStats\Models\Traits\FormatScoreTrait;
use PRStats\Models\Traits\WasSeenRecentlyTrait;

class Match extends Model
{
    use WasSeenRecentlyTrait, FormatScoreTrait;

    protected $guarded = ['id'];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)
            ->withTimestamps()
            ->withPivot(['score', 'kills', 'deaths', 'team'])
            ->orderBy('match_player.score', 'desc');
    }

    public function lengthInMinutes()
    {
        return $this->updated_at->diffInMinutes($this->created_at);
    }

    public function lengthForHumans()
    {
        $diff = $this->updated_at->diffForHumans($this->created_at, CarbonInterface::DIFF_ABSOLUTE);
        return str_replace(['seconds', 'second', 'minutes', 'minute', 'hours', 'hour'], ['sec', 'sec', 'min', 'min', 'hr', 'hr'], $diff);
    }

    public function pivotLengthForHumans()
    {
        $diff = $this->pivot->updated_at->diffForHumans($this->pivot->created_at, CarbonInterface::DIFF_ABSOLUTE);
        return str_replace(['seconds', 'second', 'minutes', 'minute', 'hours', 'hour'], ['sec', 'sec', 'min', 'min', 'hr', 'hr'], $diff);
    }

    public function getMapImageName()
    {
        return strtolower(str_replace(' ', '', $this->map->name));
    }

    public function getMapImageUrl($image = 'tile')
    {
        return '//www.realitymod.com/mapgallery/images/maps/'.$this->getMapImageName().'/'.$image.'.jpg';
    }

    public function getNavigationMapImageUrl()
    {
        if (empty($this->gamemode)) {
            return $this->getMapImageUrl();
        }

        return '//www.realitymod.com/mapgallery/images/maps/'.$this->getMapImageName().'/mapoverview_'.$this->gamemode.'_64.jpg';
    }

    public function getLink()
    {
        $slug = Str::slug($this->map->name);
        return route('match', [$this->id, $slug]);
    }

    public function teamPlayers($team)
    {
        return $this->players
            ->filter(function ($player) use ($team) {
                return $player->pivot->team == $team;
            });
    }

    public function team1Players()
    {
        return $this->teamPlayers($this->team1_name);
    }

    public function team2Players()
    {
        return $this->teamPlayers($this->team2_name);
    }


}

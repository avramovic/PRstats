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

    public function players()
    {
        return $this->belongsToMany(Player::class)->withTimestamps()->withPivot(['score', 'kills', 'deaths', 'team']);
    }

    public function formatFromToTime()
    {
        if ($this->created_at->format('Y-m-d') == $this->updated_at->format('Y-m-d')) {
            return $this->created_at->format('Y-m-d')." from ".$this->created_at->format('H:i')." to ".$this->updated_at->format('H:i');
        }

        return "from ".$this->created_at->format('Y-m-d \a\t H:i')." to ".$this->updated_at->format('Y-m-d \a\t H:i');

    }

    public function lengthInMinutes()
    {
        return $this->updated_at->diffInMinutes($this->created_at);
    }

    public function lengthForHumans()
    {
        return $this->updated_at->diffForHumans($this->created_at, CarbonInterface::DIFF_ABSOLUTE);
    }

    public function getMapImageName()
    {
        return strtolower(str_replace(' ', '', $this->map));
    }

    public function getMapImageUrl($image = 'tile')
    {
        return '//www.realitymod.com/mapgallery/images/maps/'.$this->getMapImageName().'/'.$image.'.jpg';
    }


    public function getLink()
    {
        $slug = Str::slug($this->map);
        return route('match', [$this->id, $slug]);
    }

}

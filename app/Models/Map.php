<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Map extends Model
{
    protected $guarded = [];

    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    protected function getMapImageName()
    {
        return strtolower(str_replace(' ', '', $this->name));
    }

    public function getOriginalMapImageUrl($image = 'tile')
    {
        return 'https://www.realitymod.com/mapgallery/images/maps/'.$this->getMapImageName().'/'.$image.'.jpg';
    }

    public function getBannerImagePath()
    {
        return 'maps' . DIRECTORY_SEPARATOR
            . $this->slug . DIRECTORY_SEPARATOR
            . 'banner.png';
    }

    public function getTileImagePath()
    {
        return 'maps' . DIRECTORY_SEPARATOR
            . $this->slug . DIRECTORY_SEPARATOR
            . 'tile.png';
    }

    public function getLink()
    {
        $slug = Str::slug($this->name);
        return route('map', [$this->id, $slug]);
    }

    public function __toString()
    {
        return $this->name;
    }
}

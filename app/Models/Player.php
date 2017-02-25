<?php

namespace PRStats\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $clanTag = null;

//    public function setGameName($gameName) {
//        $this->name = $gameName;
//        $spacePos = strpos($gameName, ' ');
//        if ($spacePos !== false) {
////            $parts = explode(' ', $gameName);
//            $this->clanTag = substr($gameName, 0, $spacePos);
//            $this->name = substr($gameName, $spacePos);
//        }
//    }

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
}

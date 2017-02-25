<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use PRStats\Http\Requests;
use PRStats\Models\Clan;
use PRStats\Models\Player;
use PRStats\Models\Server;

class Home extends Controller
{
    //
    public function index()
    {
        //top players
        $players = Player::with('clan')
            ->where('updated_at', '>', Carbon::now()->subMonth())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        //top clans
        $clans = Clan::where('updated_at', '>', Carbon::now()->subWeek())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        //top servers
        $servers = Server::where('updated_at', '>', Carbon::now()->subDay())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('home', ['players' => $players, 'clans' => $clans, 'servers' => $servers]);
    }

    public function clan($id, $slug)
    {
        $clan = Clan::with('players')->where('id', $id)->firstOrFail();
        return view('clan', ['clan'=>$clan]);
    }

    public function server($id, $slug)
    {
        $server = Server::with('players')->where('id', $id)->firstOrFail();

        return view('server', ['server' => $server]);
    }

    public function player($pid, $slug)
    {
        $player = Player::with('clan')->where('pid', $pid)->firstOrFail();

        return view('player', ['player'=>$player]);
    }

}

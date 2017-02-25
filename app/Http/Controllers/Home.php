<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
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

//        dd($players->toArray(), $clans->toArray(), $servers->toArray());

        return view('home', ['players' => $players, 'clans' => $clans, 'servers' => $servers]);
    }

    public function clans()
    {
        //top clans
        $clans = Clan::where('updated_at', '>', Carbon::now()->subWeek())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('clans', ['clans' => $clans]);
    }

    public function clan($id, $slug)
    {
        $clan = Clan::with('players')->where('id', $id)->firstOrFail();
        return view('clan', ['clan'=>$clan]);
    }

    public function servers()
    {
        //top servers
        $servers = Server::where('updated_at', '>', Carbon::now()->subDay())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('servers', ['servers' => $servers]);
    }

    public function server($id, $slug)
    {
        $server = Server::with('players')->where('id', $id)->firstOrFail();

        return view('server', ['server' => $server]);
    }

    public function players()
    {
        //top players
        $players = Player::with('clan')
            ->where('updated_at', '>', Carbon::now()->subMonth())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('players', ['players' => $players]);
    }



    public function player($pid, $slug)
    {
        $player = Player::with('clan')->where('pid', $pid)->firstOrFail();

        return view('player', ['player'=>$player]);
    }

}

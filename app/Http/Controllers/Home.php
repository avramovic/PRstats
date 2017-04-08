<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PRStats\Models\Clan;
use PRStats\Models\Player;
use PRStats\Models\Server;

class Home extends Controller
{
    public function index()
    {
        //top players
        $players = Player::with('clan')
            ->where('updated_at', '>', Carbon::now()->startOfMonth())
            ->orderBy('monthly_score', 'desc')
            ->take(50)
            ->get();

        return view('home', ['players' => $players]);
    }

    public function clans()
    {
        //top clans
        $clans = Clan::orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('clans', ['clans' => $clans]);
    }

    public function clanSearch(Request $request)
    {
        //top players
        $clans = Clan::with('players')
            ->where('name', 'LIKE', '%'.$request->q.'%')
            ->orderBy('name', 'asc')
            ->take(50)
            ->get();

        return view('clans', ['clans' => $clans, 'query'=>$request->q]);
    }

    public function clan($id, $slug)
    {
        $clan = Clan::where('id', $id)->firstOrFail();

        $players = $clan->players()->take(50)->orderBy('total_score', 'desc')->get();

        return view('clan', ['clan' => $clan, 'players' => $players]);
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
        $server = Server::where('id', $id)->firstOrFail();

        $timestamp = Carbon::parse('2017-04-07 00:00:00');

        if ($server->wasSeenRecently()) {
            $timestamp = Carbon::now()->subMinutes(5);
        }

        $players = $server->players()
            ->where('updated_at', '>', $timestamp)
            ->take(64)
            ->orderBy('total_score', 'desc')->get();

        return view('server', ['server' => $server, 'players' => $players]);
    }

    public function players()
    {
        //top players
        $players = Player::with('clan')
//            ->where('updated_at', '>', Carbon::now()->subMonth())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('players', ['players' => $players]);
    }

    public function playerSearch(Request $request)
    {
        //top players
        $players = Player::with('clan')
            ->where('name', 'LIKE', '%'.$request->q.'%')
            ->orderBy('name', 'asc')
            ->take(50)
            ->get();

        return view('players', ['players' => $players, 'query'=>$request->q]);
    }


    public function player($pid, $slug)
    {
        $player = Player::with('clan', 'server')->where('pid', $pid)->firstOrFail();

        $players = null;

        if ($player->clan) {
            $players = $player->clan->players->sortByDesc(function($item) {
                return $item->total_score;
            });
        }

        return view('player', ['player' => $player, 'clanPlayers' => $players]);
    }

}

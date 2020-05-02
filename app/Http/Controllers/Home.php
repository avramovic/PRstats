<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PRStats\Models\Clan;
use PRStats\Models\Match;
use PRStats\Models\Player;
use PRStats\Models\Server;

class Home extends Controller
{
    protected $banned = [
        '100384079',
    ];

    public function index()
    {
        //top players
        $players = Player::with('clan')
            ->where('updated_at', '>', Carbon::now()->startOfMonth())
            ->orderBy('monthly_score', 'desc')
            ->take(50)
            ->get();

        $newest = Player::with('clan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $longest = Player::with('clan')
            ->orderBy('minutes_played', 'desc')
            ->limit(10)
            ->get();

        $mostKills = Player::with('clan')
            ->orderBy('total_kills', 'desc')
            ->limit(10)
            ->get();

        $mostDeaths = Player::with('clan')
            ->orderBy('total_deaths', 'desc')
            ->limit(10)
            ->get();

        return view('home', [
            'players'    => $players,
            'newest'     => $newest,
            'longest'    => $longest,
            'mostKills'  => $mostKills,
            'mostDeaths' => $mostDeaths,
        ]);
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

        return view('clans', ['clans' => $clans, 'query' => $request->q]);
    }

    public function clan($id, $slug)
    {
        $clan = Clan::where('id', $id)->firstOrFail();

        $players = $clan->players()->take(50)->orderBy('total_score', 'desc')->get();

        return view('clan', ['clan' => $clan, 'players' => $players, 'server' => $clan->last_player_seen->server]);
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
        $threeMinAgo = Carbon::now()->subMinute(3);
        $server      = Server::where('id', $id)->with([
            'matches'         => function ($q) {
                return $q->orderBy('id', 'desc')->limit(50);
            },
            'matches.players' => function ($q) use ($threeMinAgo) {
                return $q->where('match_player.updated_at', '>=', $threeMinAgo)
                    ->orderBy('match_player.score', 'desc');
            }])->firstOrFail();

//        $timestamp = Carbon::parse('2017-04-07 00:00:00');
//
//        if ($server->wasSeenRecently()) {
//            $timestamp = Carbon::now()->subMinutes(5);
//        }
//
//        if ($server->wasSeenRecently()) {
//            $players = $server->players()
//                ->where('updated_at', '>', $timestamp)
//                ->take(64)
//                ->orderBy('last_score', 'desc')->get();
//        } else {
//            $players = $server->players()
//                ->where('updated_at', '>', $timestamp)
//                ->take(64)
//                ->orderBy('total_score', 'desc')->get();
//
//        }

        return view('server', ['server' => $server]);
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
            ->whereNotIn('pid', $this->banned)
            ->orderBy('name', 'asc')
            ->take(50)
            ->get();

        return view('players', ['players' => $players, 'query' => $request->q]);
    }


    public function player($pid, $slug)
    {
        if (in_array($pid, $this->banned)) {
            abort(404);
        }

        $player = Player::with(['clan', 'server', 'matches' => function ($q) {
            return $q->orderBy('id', 'desc')->limit(50);
        }])->where('pid', $pid)->firstOrFail();

        $players = null;

        if ($player->clan) {
            $players = $player->clan->players->sortByDesc(function ($item) {
                return $item->total_score;
            });
        }

        return view('player', ['player' => $player, 'clanPlayers' => $players, 'server' => $player->server]);
    }

    public function matchDetails($id, $map)
    {
        $match = Match::with(['server', 'players' => function ($q) {
            return $q->orderBy('match_player.score', 'desc');
        }])->findOrFail($id);

        return view('match', ['match' => $match]);
    }

}

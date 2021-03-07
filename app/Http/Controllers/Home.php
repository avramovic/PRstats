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
            ->withCount(['matches' => function ($q) {
                return $q->where('match_player.updated_at', '>=', Carbon::now()->startOfMonth());
            }])
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
        $clans = Clan::withCount('players')
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('clans', ['clans' => $clans]);
    }

    public function clanSearch(Request $request)
    {
        //top players
        $clans = Clan::withCount('players')
            ->where('name', 'LIKE', '%'.$request->q.'%')
            ->orderBy('name', 'asc')
            ->take(50)
            ->get();

        return view('clans', ['clans' => $clans, 'query' => $request->q]);
    }

    public function clan($id, $slug)
    {
        $clan = Clan::where('id', $id)->firstOrFail();

        $players = $clan->players()->withCount(['matches'])->orderBy('total_score', 'desc')->get();

        return view('clan', ['clan' => $clan, 'players' => $players, 'server' => $clan->last_player_seen->server]);
    }

    public function servers()
    {
        //top servers
        $servers = Server::withCount('matches')
            ->where('updated_at', '>', Carbon::now()->subWeek())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('prstats.servers', ['servers' => $servers]);
    }

    public function server($id, $slug)
    {
        $threeMinAgo = Carbon::now()->subMinutes(4);
        /**
         * @var Server
         */
        $server = Server::findOrFail($id);

        $previousMatches = $server->matches()
//            ->when($server->wasSeenRecently(), function())
            ->where('updated_at', '<', $threeMinAgo)
            ->orderBy('id', 'desc')
            ->paginate();

        $lastMatch = $server->matches()
            ->with(['players' => function ($q) use ($threeMinAgo) {
                return $q->where('match_player.updated_at', '>=', $threeMinAgo)
                    ->orderBy('match_player.score', 'desc');
            }])
//            ->where('updated_at', '>=', $threeMinAgo)
            ->orderBy('id', 'desc')
            ->first();

        return view('prstats.server', [
            'server'          => $server,
            'previousMatches' => $previousMatches,
            'lastMatch'       => $lastMatch,
        ]);
    }

    public function players()
    {
        //top players
        $players = Player::with('clan')->withCount('matches')
//            ->where('updated_at', '>', Carbon::now()->subMonth())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('players', ['players' => $players]);
    }

    public function playerSearch(Request $request)
    {
        //top players
        $players = Player::with('clan')->withCount('matches')
            ->where('name', 'LIKE', '%'.$request->q.'%')
            ->orWhere('slug', 'LIKE', '%'.$request->q.'%')
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

        $player = Player::with(['server',
            'matches'      => function ($q) {
                return $q->orderBy('match_player.updated_at', 'desc')->limit(50);
            },
            'clan.players' => function ($q) {
                return $q->withCount('matches')->orderBy('total_score', 'desc');
            }])
            ->where('pid', $pid)
            ->firstOrFail();

        return view('player', ['player' => $player, 'clanPlayers' => $player->clan ? $player->clan->players : collect([]), 'server' => $player->server]);
    }

    public function playerShorUrl($slug)
    {
        $candidates = Player::where('slug', 'like', $slug)->get();

//        dd($candidates->toArray());

        if ($candidates->count() == 0) {
            abort(404);
        } elseif ($candidates->count() == 1) {
            $player = $candidates->first();
            return redirect()->route('player', [$player->pid, $player->slug]);
        } else {
            return redirect()->route('players.search', ['q' => $slug]);
        }
    }

    public function matchDetails($id, $map)
    {
        $match = Match::with(['server', 'players' => function ($q) {
            return $q->orderBy('match_player.score', 'desc');
        }])->findOrFail($id);

        return view('match', ['currentMatch' => $match]);
    }

}

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
            ->where('updated_at', '>=', Carbon::now()->startOfMonth())
            ->orderBy('monthly_score', 'desc')
            ->paginate(50);

        $newest = Player::with('clan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $longest = Player::with(['clan', 'matches'])
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

        return view('prstats.home', [
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
            ->paginate(50);

        return view('prstats.clans', ['clans' => $clans]);
    }

    public function clanSearch(Request $request)
    {
        //top players
        $clans = Clan::withCount('players')
            ->where('name', 'LIKE', '%'.$request->q.'%')
            ->orderBy('name', 'asc')
            ->paginate(50);

        return view('prstats.clans', ['clans' => $clans, 'query' => $request->q]);
    }

    public function clan($id, $slug)
    {
        $clan = Clan::where('id', $id)->firstOrFail();

        $players = $clan->players()->withCount(['matches'])->orderBy('total_score', 'desc')->get();

        return view('prstats.clan', ['clan' => $clan, 'players' => $players, 'server' => $clan->last_player_seen->server]);
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
            ->where('updated_at', '<', $threeMinAgo)
            ->orderBy('id', 'desc')
            ->paginate();

        $lastMatch = $server->matches()
            ->with(['players' => function ($q) use ($threeMinAgo) {
                return $q->where('match_player.updated_at', '>=', $threeMinAgo)
                    ->orderBy('match_player.score', 'desc');
            }])
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
            ->orderBy('total_score', 'desc')
            ->paginate(50);

        return view('prstats.players', ['players' => $players]);
    }

    public function playerSearch(Request $request)
    {
        //top players
        $players = Player::with('clan')->withCount('matches')
            ->where('name', 'LIKE', '%'.$request->q.'%')
            ->orWhere('slug', 'LIKE', '%'.$request->q.'%')
            ->orderBy('name', 'asc')
            ->paginate(50);

        return view('prstats.players', ['players' => $players, 'query' => $request->q]);
    }


    public function player($pid, $slug)
    {
        if (in_array($pid, $this->banned)) {
            abort(404);
        }

        $threeMinAgo = Carbon::now()->subMinutes(3);

        $player = Player::with(['server',
            'clan.players' => function ($q) {
                return $q->withCount('matches')->orderBy('total_score', 'desc');
            }])
            ->where('pid', $pid)
            ->firstOrFail();

        $matches = $player->matches()
            ->with(['server'])
            ->where('matches.updated_at', '<', $threeMinAgo)
            ->orderBy('id', 'desc')
            ->paginate();

        $lastMatch = $player->matches()
            ->orderBy('id', 'desc')
            ->first();

        return view('prstats.player', [
            'player'      => $player,
            'clanPlayers' => $player->clan ? $player->clan->players : collect([]),
            'server'      => $player->server,
            'matches'     => $matches,
            'lastMatch'   => $lastMatch,
        ]);
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

        return view('prstats.match', ['match' => $match]);
    }

}

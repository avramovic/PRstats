<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PRStats\Models\Player;

class PlayerController extends Controller
{

    public function index()
    {
        //top players
        $players = Player::with('clan')->withCount('matches')
            ->orderBy('total_score', 'desc')
            ->paginate(50);

        $newest = Player::with('clan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $longest = Player::with(['clan'])
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

        return view('prstats.players', [
            'players'    => $players,
            'newest'     => $newest,
            'mostKills'  => $mostKills,
            'mostDeaths' => $mostDeaths,
            'longest'    => $longest,
        ]);
    }

    public function search(Request $request)
    {
        //top players
        $players = Player::with('clan')->withCount('matches')
            ->where('name', 'LIKE', '%'.$request->q.'%')
            ->orWhere('slug', 'LIKE', '%'.$request->q.'%')
            ->orderBy('name', 'asc')
            ->paginate(50);

        return view('prstats.players', ['players' => $players, 'query' => $request->q]);
    }


    public function show($pid, $slug)
    {
        if (!is_numeric($pid)) {
            $player = Player::with(['server',
                'clan.players' => function ($q) {
                    return $q->withCount('matches')->orderBy('total_score', 'desc');
                }])
                ->where('pid', $pid)
                ->firstOrFail();

            return redirect($player->getLink(), 301);
        }

        $threeMinAgo = Carbon::now()->subMinutes(3);

        $player = Player::with(['server',
            'clan.players' => function ($q) {
                return $q->withCount('matches')->orderBy('total_score', 'desc');
            }])
            ->findOrFail($pid);

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

    public function shortUrl($slug)
    {
        $candidates = Player::where('slug', 'like', $slug)->get();

        if ($candidates->count() == 0) {
            abort(404);
        } elseif ($candidates->count() == 1) {
            $player = $candidates->first();
            return redirect()->route('player', [$player->id, $player->slug]);
        } else {
            return redirect()->route('players.search', ['q' => $slug]);
        }
    }

}

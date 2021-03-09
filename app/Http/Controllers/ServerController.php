<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use PRStats\Models\Match;
use PRStats\Models\Server;

class ServerController extends Controller
{

    public function index()
    {
        //top servers
        $servers = Server::withCount('matches')
            ->where('updated_at', '>', Carbon::now()->subWeek())
            ->orderBy('total_score', 'desc')
            ->take(50)
            ->get();

        return view('prstats.servers', ['servers' => $servers]);
    }

    public function show($id, $slug)
    {
        $threeMinAgo = Carbon::now()->subMinutes(4);
        /**
         * @var Server
         */
        $server = Server::findOrFail($id);

        $server->weeklyActivity();

        $previousMatches = $server->matches()
            ->where('updated_at', '<', $threeMinAgo)
            ->orderBy('id', 'desc')
            ->paginate(25);

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

    public function match($id, $map)
    {
        $match = Match::with(['server', 'players' => function ($q) {
            return $q->orderBy('match_player.score', 'desc');
        }])->findOrFail($id);

        return view('prstats.match', ['match' => $match]);
    }

}

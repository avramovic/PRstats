<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PRStats\Models\Clan;
use PRStats\Models\Player;
use PRStats\Models\Server;

class HomeController extends Controller
{
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

        $mostKills = Player::with('clan')
            ->orderBy('monthly_kills', 'desc')
            ->limit(10)
            ->get();

        $mostDeaths = Player::with('clan')
            ->orderBy('monthly_deaths', 'desc')
            ->limit(10)
            ->get();

        return view('prstats.home', [
            'players'    => $players,
            'mostKills'  => $mostKills,
            'mostDeaths' => $mostDeaths,
        ]);
    }

    public function search(Request $request)
    {
        $players = Player::where('name', 'like', '%'.$request->search.'%')->get();
        $clans   = Clan::where('name', 'like', '%'.$request->search.'%')->get();
        $servers = Server::where('name', 'like', '%'.$request->search.'%')->get();

        $results = [];

        foreach ($players as $player) {
            $results[] = [
                'value' => $player->getLink(),
                'label' => $player->name,
                'icon'  => 'user',
            ];
        }

        foreach ($clans as $clan) {
            $results[] = [
                'value' => $clan->getLink(),
                'label' => $clan->name,
                'icon'  => 'users',
            ];
        }

        foreach ($servers as $server) {
            $results[] = [
                'value' => $server->getLink(),
                'label' => $server->name,
                'icon'  => 'server',
            ];
        }

        return response()->json($results);
    }
}

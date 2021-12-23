<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PRStats\Models\Clan;
use PRStats\Models\Device;
use PRStats\Models\Map;
use PRStats\Models\Player;
use PRStats\Models\Server;
use PRStats\Models\Subscription;

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
        $maps    = Map::where('name', 'like', '%'.$request->search.'%')->get();

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

        foreach ($maps as $map) {
            $results[] = [
                'value' => $map->getLink(),
                'label' => $map->name,
                'icon'  => 'map',
            ];
        }

        return response()->json($results);
    }

    public function notifications()
    {
        $latest = Subscription::with('player')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $most = Player::withCount('subscriptions')
            ->orderBy('subscriptions_count', 'desc')
            ->limit(10)
            ->get()
            ->reject(function (Player $player) {
                return $player->subscriptions_count == 0;
            });

        return view('prstats.notifications', [
            'latest' => $latest,
            'most'   => $most,
        ]);
    }

    public function getNotifications(Request $request)
    {
        /** @var Device $device */
        $device = Device::with('subscriptions')->where('uuid', $request->device_uuid)->first();

        if (!$device) {
            abort(404);
        }

        $players = Player::whereIn('id', $device->subscriptions->pluck('player_id'))->paginate();

        return view('partials.players.players_table', [
            'width'   => '12',
            'slot'    => '',
            'metric'  => 'total',
            'players' => $players,
        ]);
    }
}

<?php

namespace PRStats\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use PRStats\Models\Clan;
use PRStats\Models\Map;

class MapController extends Controller
{

    public function index()
    {
        //top clans
        $maps = Map::withCount('matches')
            ->with(['matches' => function ($q) {
                $q->orderBy('updated_at', 'desc')
                    ;//->limit(1);
            }])
            ->get();

        $maps->map(function($map) {
            $map->lastMatch = $map->matches->first();
        });

        return view('prstats.maps', ['maps' => $maps->sortBy('matches_count')]);
    }

    public function show($id, $slug, Request $request)
    {
        $clan = Clan::where('id', $id)->firstOrFail();

        /** @var Collection $players */
        $players = $clan->players()->withCount(['matches'])->orderBy('total_score', 'desc')->get();

        $playerDetails = null;
        if ($players->count() > 0) {
            $playerDetails = $players->find($request->query('p', $players->first()->id)) ?? $players->first();
        }

        return view('prstats.clan', [
            'clan'          => $clan,
            'players'       => $players,
            'playerDetails' => $playerDetails,
//            'server'        => $clan->last_player_seen->server,
        ]);
    }

}

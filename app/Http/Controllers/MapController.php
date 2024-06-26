<?php

namespace PRStats\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use PRStats\Models\Map;

class MapController extends Controller
{

    public function index()
    {
        $maps = Map::withCount('matches')
            ->get();

        $maps->map(function ($map) {
            $map->lastMatch = $map->matches()->orderBy('updated_at', 'desc')->first();
        });

        return view('prstats.maps', ['maps' => $maps->sortByDesc('matches_count')]);
    }

    public function show($id, $slug, Request $request)
    {
        $map = Map::where('id', $id)->firstOrFail();

        /** @var Collection $players */
        $matches = $map->matches()
            ->with(['server'])
            ->withCount(['players'])
            ->orderBy('updated_at', 'desc')
            ->paginate(25);

        return view('prstats.map', [
            'map'     => $map,
            'matches' => $matches,
//            'playerDetails' => $playerDetails,
//            'server'        => $clan->last_player_seen->server,
        ]);
    }

}

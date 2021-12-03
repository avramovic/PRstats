<?php

namespace PRStats\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use PRStats\Models\Clan;

class ClanController extends Controller
{

    public function index()
    {
        //top clans
        $clans = Clan::withCount('players')
            ->orderBy('total_score', 'desc')
            ->paginate(50);

        //new clans
        $newest = Clan::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $populous = Clan::withCount('players')
            ->orderBy('players_count', 'desc')
            ->limit(10)
            ->get();

        return view('prstats.clans', [
            'clans'    => $clans,
            'newest'   => $newest,
            'populous' => $populous,
        ]);
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

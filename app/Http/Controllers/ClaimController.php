<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PRStats\Models\Claim;
use PRStats\Models\Player;
use Ramsey\Uuid\Uuid;

class ClaimController extends Controller
{

    public function index()
    {
        return view('prstats.claim.index', [
            'latest' => collect([]),
            'most' => collect([]),
        ]);
    }

    public function player($pid)
    {
        $player = Player::with(['clan'])
            ->findOrFail($pid);

        $threeMinAgo = Carbon::now()->subMinutes(3);

        $matches = $player->matches()
            ->with(['server', 'map'])
            ->when($player->wasSeenRecently(), function ($q) use ($threeMinAgo) {
                $q->where('matches.updated_at', '<', $threeMinAgo);
            })
            ->orderBy('id', 'desc')
            ->paginate(25);

        $lastMatch = $player->matches()
            ->with(['server', 'map'])
            ->orderBy('id', 'desc')
            ->first();

        return view('prstats.claim.player', [
            'player'    => $player,
            'matches'   => $matches,
            'lastMatch' => $lastMatch,
        ]);
    }

    public function store($pid, Request $request)
    {
        /** @var Player $player */
        $player = Player::with(['clan'])
            ->findOrFail($pid);

        $claim = $player->claims()->updateOrCreate(['email' => \Auth::guest() ? $request->email : \Auth::user()->email], [
            'uuid'         => Uuid::uuid4(),
            'code'         => strtoupper(Str::random(6)),
            'old_clan_tag' => $player->clan ? $player->clan->name : null,
            'user_id'      => \Auth::guest() ? null : \Auth::user()->id,
        ]);

        return view('prstats.claim.show', [
            'claim'  => $claim,
            'player' => $player,
        ]);
    }

    public function howTo($uuid = null)
    {
        if ($uuid) {
            $claim  = Claim::with(['player'])->withTrashed()->where('uuid', $uuid)->firstOrFail();
            $player = $claim->player;
        } else {
            $claim  = null;
            $player = null;
        }

        return view('prstats.claim.howto', [
            'claim'  => $claim,
            'player' => $player,
        ]);
    }

}

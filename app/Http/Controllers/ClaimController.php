<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PRStats\Models\Claim;
use PRStats\Models\Player;
use PRStats\Notifications\ClaimRequestedNotification;
use Ramsey\Uuid\Uuid;

class ClaimController extends Controller
{

    public function index()
    {
        return view('prstats.claim.index', [
            'latest' => collect([]),
            'most'   => collect([]),
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
        if (\Auth::guest()) {
            return redirect()->route('claim.index');
        }

        /** @var Player $player */
        $player = Player::with(['clan'])
            ->findOrFail($pid);

        /** @var Claim $claim */
        $claim = $player->claims()->where('email', \Auth::user()->email)->first();

        if ($claim) {
            return redirect()->route('claim.show', $claim->uuid);
        }

        /** @var Claim $claim */
        $claim = $player->claims()->create([
            'email'        => \Auth::user()->email,
            'uuid'         => Uuid::uuid4(),
            'code'         => strtoupper(Str::random(6)),
            'old_clan_tag' => $player->clan ? $player->clan->name : null,
            'user_id'      => \Auth::user()->id,
        ]);

        \Auth::user()->notify(new ClaimRequestedNotification($claim));

        return redirect()->route('claim.show', $claim->uuid);
    }

    public function show($uuid)
    {
        $claim  = Claim::with(['player'])->withTrashed()->where('uuid', $uuid)->firstOrFail();
        $player = $claim->player;

        return view('prstats.claim.show', [
            'claim'  => $claim,
            'player' => $player,
            'latest' => collect([]),
            'most'   => collect([]),
        ]);
    }

}

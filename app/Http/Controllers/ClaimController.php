<?php

namespace PRStats\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PRStats\Models\Claim;
use PRStats\Models\Player;
use PRStats\Models\User;
use PRStats\Notifications\ClaimRequestedNotification;
use Ramsey\Uuid\Uuid;

class ClaimController extends Controller
{

    public function index()
    {

        $claims = Claim::onlyTrashed()
            ->with(['player'])
            ->orderBy('deleted_at', 'desc')
            ->limit(10)
            ->get();

        $users = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $claims = $claims->reject(function (Claim $claim) {
            return empty($claim->player);
        });

        return view('prstats.claim.index', [
            'claims' => $claims,
            'users'  => $users,
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

        if (!empty($player->user_id)) {
            abort(403);
        }

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

        $claims = Claim::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->limit(10)
            ->get();

        $users = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $claims = $claims->reject(function (Claim $claim) {
            return empty($claim->player);
        });

        return view('prstats.claim.show', [
            'claim'  => $claim,
            'player' => $player,
            'claims' => $claims,
            'users'  => $users,
        ]);
    }

}

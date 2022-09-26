<?php

namespace PRStats\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PRStats\Models\Device;
use PRStats\Models\Player;
use Storage;

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
            $player = Player::where('pid', $pid)
                ->firstOrFail();

            return redirect($player->getLink(), 301);
        }

        $threeMinAgo = Carbon::now()->subMinutes(3);

        /** @var Player $player */
        $player = Player::with(['server',
            'clan.players' => function ($q) {
                return $q->withCount('matches')->orderBy('total_score', 'desc');
            }])
            ->withCount(['subscriptions'])
            ->withTrashed()
            ->findOrFail($pid);

        if ($player->trashed()) {
            if (\Auth::guest() || !\Auth::user()->canEdit($player)) {
                abort(404);
            }
        }

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

        return view('prstats.player', [
            'player'       => $player,
            'clanPlayers'  => $player->clan ? $player->clan->players : collect([]),
            'server'       => $player->server,
            'matches'      => $matches,
            'lastMatch'    => $lastMatch,
            'hasSignature' => Storage::exists($player->getSignaturePath()),
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

    public function findByName(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required|string']);
        if ($validator->fails()) {
            abort(422, 'Name parameter is required!');
        }

        $player = Player::where('name', $request->name)->firstOrFail();

        return redirect()->route('player', [$player->id, $player->slug]);
    }

    public function toggleSubscribe(Request $request)
    {
        /** @var Player $player */
        $player = Player::findOrFail($request->player_id);
        $device = Device::firstOrCreate(['uuid' => $request->device_uuid]);

        $preApproved = $player->user ? $player->user->auto_approve_subscriptions : true;

        $sub = $player->subscriptions()
            ->where('device_id', $device->id)
            ->first();

        if ($sub) {
            $sub->delete();
        } else {
            $sub = $player->subscriptions()
                ->firstOrCreate(['device_id' => $device->id], ['approved_at' => $preApproved ? Carbon::now() : null]);
        }

        return response()->json([
            'count' => $player->subscriptions()->count(),
            'subscription'  => $sub->exists ? $sub->toArray() : null,
        ]);
    }

    public function checkSubscription(Request $request)
    {
        /** @var Player $player */
        $player = Player::findOrFail($request->player_id);
        $device = Device::firstOrCreate(['uuid' => $request->device_uuid]);

        $sub = $player->subscriptions()
            ->where('device_id', $device->id)
            ->first();

        return response()->json([
            'subscription' => $sub->toArray(),
        ]);
    }

    public function toggleVisibility($pid)
    {
        $player = Player::withTrashed()->findOrFail($pid);

        if ($player->trashed()) {
            $player->restore();
        } else {
            $player->delete();
        }

        return redirect($player->getLink());
    }

}

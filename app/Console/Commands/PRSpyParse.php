<?php

namespace PRStats\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use PRStats\Jobs\DownloadMapImagesJob;
use PRStats\Jobs\MakePlayerAvatarJob;
use PRStats\Jobs\MakePlayerSignatureJob;
use PRStats\Models\Clan;
use PRStats\Models\Map;
use PRStats\Models\Match;
use PRStats\Models\Player;
use PRStats\Models\Server;

class PRSpyParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prspy:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse PRSpy data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = microtime(true);
        $data  = file_get_contents('https://servers.realitymod.com/api/ServerInfo');
        $diff  = microtime(true) - $start;
        $this->line("[".date('H:i:s')."] Downloaded prspy data in {$diff} seconds");
        $this->line("");
        $data    = json_decode(trim($data, '"'));
        $servers = isset($data->servers) ? $data->servers : [];

        $start = microtime(true);

        foreach ($servers as $serverData) {

            if (empty($serverData->serverId)) {
                continue;
            }

            $newgame    = false;
            $serverName = trim(preg_replace('/^\[.*?\]/is', '', $this->decodeName($serverData->properties->hostname), 1));

            if (!empty($serverData->properties->password) || ($serverData->properties->numplayers < 2) || (stripos($serverData->properties->gametype, 'coop') !== false)) {
                $this->line("[".date('H:i:s')."] Skipping ".$serverName." (no players / password / coop)");
                continue;
            }

            $server = Server::where('server_id', $serverData->serverId)
                ->first();

            if (!$server) {
                $server = Server::where('name', $serverName)
                    ->whereNull('server_id')
                    ->first();
            }

            if ($server == null) {
                $server               = new Server;
                $server->server_id    = $serverData->serverId;
                $server->games_played = 1;
                $newgame              = true;
                $server->save();
            }

            $map = Map::where('name', $serverData->properties->mapname)
                ->first();

            if (!$map) {
                $map = Map::create([
                    'name' => $serverData->properties->mapname,
                    'slug' => Str::slug($serverData->properties->mapname),
                ]);
                dispatch(new DownloadMapImagesJob($map));
            }

            if ($server->last_map != $serverData->properties->mapname) {
                $newgame          = true;
                $server->last_map = $serverData->properties->mapname;

                /** @var Match $match */
                $match = Match::create([
                    'server_id'  => $server->id,
                    'map_id'     => $map->id,
                    'team1_name' => $serverData->properties->bf2_team1,
                    'team2_name' => $serverData->properties->bf2_team2,
                ]);
            } else {
                $match = $server->matches()
                    ->with(['map'])
                    ->where('map_id', $map->id)
                    ->orderBy('id', 'desc')
                    ->first();

                //just a safe check
                if (!$match || $match->map->name != $serverData->properties->mapname) {
                    $match = Match::create([
                        'server_id'  => $server->id,
                        'map_id'     => $map->id,
                        'gamemode'   => $serverData->properties->gametype,
                        'team1_name' => $serverData->properties->bf2_team1,
                        'team2_name' => $serverData->properties->bf2_team2,
                    ]);
                } else {
                    $match->update([
                        'updated_at' => Carbon::now(),
                        'gamemode'   => $serverData->properties->gametype,
                    ]);
                }
            }

            $server->name           = $serverName;
            $server->server_id      = $serverData->serverId;
            $server->country        = $serverData->countryFlag;
            $server->num_players    = $serverData->properties->numplayers;
            $server->max_players    = $serverData->properties->maxplayers;
            $server->reserved_slots = $serverData->properties->bf2_reservedslots;
            $server->os             = $serverData->properties->bf2_os;

            if ($serverData->properties->bf2_d_dl) {
//                $server->br_index    = $serverData->properties->bf2_d_idx;
                $server->br_download = filter_var($serverData->properties->bf2_d_dl, FILTER_VALIDATE_URL);
            }

            $server->server_text       = $serverData->properties->bf2_sponsortext;
            $server->server_logo       = filter_var($serverData->properties->bf2_sponsorlogo_url, FILTER_VALIDATE_URL);
            $server->community_website = filter_var($serverData->properties->bf2_communitylogo_url, FILTER_VALIDATE_URL);

            //process numbers
            $server->team1_name = $serverData->properties->bf2_team1;
            $server->team2_name = $serverData->properties->bf2_team2;

            $team1_score = $team1_kills = $team1_deaths = 0;
            $team2_score = $team2_kills = $team2_deaths = 0;

            //process players & clans
            foreach ($serverData->players as $playerData) {
                $name = $this->decodeName($playerData->name);
                $pid  = collect(explode(' ', $name))->last();
                $pid  = md5($pid);

                $player = Player::where('pid', $pid)->first();
                if ($player == null) {
                    $player               = new Player;
                    $player->pid          = $pid;
                    $player->games_played = 1;
                }

                $hasClan   = (strpos($name, ' ') !== false);
                $flagToSet = false;

                if ($hasClan) {
                    $parts   = explode(' ', $playerData->name);
                    $clanTag = $parts[0];
                    $name    = $parts[1];

                    if (!empty($clanTag)) {

                        //check if setting flag or setting/changing clan
                        if (stripos($clanTag, 'prs:') === 0 && strlen($clanTag) == 6) {
                            $flagToSet = strtoupper(str_ireplace('prs:', '', $clanTag));
                            $flagToSet = ($flagToSet == 'XK') ? 'RS' : $flagToSet;
                        } else {
                            $clan = Clan::where('name', $this->decodeName($clanTag))->first();
                            if ($clan == null) {
                                $clan       = new Clan;
                                $clan->name = $this->decodeName($clanTag);
                                $clan->slug = Str::slug($clan->name);
                                $clan->save();
                            }
                            $player->clan_id = $clan->id;
                        }

                    } else {
                        $player->clan_id = null;
                    }

                } else {
                    $player->clan_id = null;
                }

                if ($flagToSet) {
                    $player->country = $flagToSet;

                    if ($player->clan_id) {
                        $player->load(['clan']);

                        if (empty($player->clan->country)) {
                            $player->clan->update(['country' => $flagToSet]);
                        }
                    }
                }

                $player->name = trim($this->decodeName($name));
                $player->slug = Str::slug($player->name);

                $player->total_score  = ($player->last_score > $playerData->score) ?
                    $player->total_score + $playerData->score :
                    $player->total_score + $playerData->score - $player->last_score;
                $player->total_kills  = ($player->last_kills > $playerData->kills) ?
                    $player->total_kills + $playerData->kills :
                    $player->total_kills + $playerData->kills - $player->last_kills;
                $player->total_deaths = ($player->last_deaths > $playerData->deaths) ?
                    $player->total_deaths + $playerData->deaths :
                    $player->total_deaths + $playerData->deaths - $player->last_deaths;

                $player->monthly_score  = ($player->last_score > $playerData->score) ?
                    $player->monthly_score + $playerData->score :
                    $player->monthly_score + $playerData->score - $player->last_score;
                $player->monthly_kills  = ($player->last_kills > $playerData->kills) ?
                    $player->monthly_kills + $playerData->kills :
                    $player->monthly_kills + $playerData->kills - $player->last_kills;
                $player->monthly_deaths = ($player->last_deaths > $playerData->deaths) ?
                    $player->monthly_deaths + $playerData->deaths :
                    $player->monthly_deaths + $playerData->deaths - $player->last_deaths;


                $player->games_played = ($player->last_score > $playerData->score) ? $player->games_played + 1 : (int)$player->games_played;

                $server->total_score  = ($player->last_score > $playerData->score) ?
                    $server->total_score + $playerData->score :
                    $server->total_score + $playerData->score - $player->last_score;
                $server->total_kills  = ($player->last_kills > $playerData->kills) ?
                    $server->total_kills + $playerData->kills :
                    $server->total_kills + $playerData->kills - $player->last_kills;
                $server->total_deaths = ($player->last_deaths > $playerData->deaths) ?
                    $server->total_deaths + $playerData->deaths :
                    $server->total_deaths + $playerData->deaths - $player->last_deaths;

                if ($playerData->team == 1) {
                    $team1_deaths += $playerData->deaths;
                    $team1_kills  += $playerData->kills;
                    $team1_score  += $playerData->score;
                } elseif ($playerData->team == 2) {
                    $team2_deaths += $playerData->deaths;
                    $team2_kills  += $playerData->kills;
                    $team2_score  += $playerData->score;
                }

                $player->last_score  = $playerData->score;
                $player->last_kills  = $playerData->kills;
                $player->last_deaths = $playerData->deaths;
                $minutes             = (int)$player->minutes_played;
                $minutes++;
                $player->minutes_played = $minutes;

                $player->server_id = $server->id;

                if (!$player->exists) {
                    $player->save();
                    dispatch(new MakePlayerAvatarJob($player));
                } else {
                    if (!$player->wasSeenRecently(10) && $player->total_score >= 10000) {
                        dispatch(new MakePlayerSignatureJob($player));
                    }
                    $player->save();
                }

                $playerTeam = ($playerData->team == 1) ? $serverData->properties->bf2_team1 : $serverData->properties->bf2_team2;

                if ($match->players->contains('id', $player->id)) {
                    $playerWithPivot = $match->players->where('id', $player->id)->first();

                    if ($playerWithPivot->team == $playerTeam) {
                        $match->players()->updateExistingPivot($player->id, [
                            'deaths' => $playerData->deaths != 0 ? $playerData->deaths : $playerWithPivot->pivot->deaths,
                            'kills'  => $playerData->kills != 0 ? $playerData->kills : $playerWithPivot->pivot->kills,
                            'score'  => $playerData->score != 0 ? $playerData->score : $playerWithPivot->pivot->score,
                        ]);
                    } else {
                        $match->players()->updateExistingPivot($player->id, [
                            'deaths' => $playerData->deaths,
                            'kills'  => $playerData->kills,
                            'score'  => $playerData->score,
                            'team'   => $playerTeam,
                        ]);
                    }

                } else {
                    $match->players()->attach($player->id, [
                        'deaths' => $playerData->deaths,
                        'kills'  => $playerData->kills,
                        'score'  => $playerData->score,
                        'team'   => $playerTeam,
                    ]);

                    $match->load(['players']);
                }
            }

            $server->team1_score  = $team1_score;
            $server->team1_kills  = $team1_kills;
            $server->team1_deaths = $team1_deaths;
            $server->team2_score  = $team2_score;
            $server->team2_kills  = $team2_kills;
            $server->team2_deaths = $team2_deaths;

            if ($newgame) {
                $server->games_played++;
            }

            $server->save();

            $this->line("[".date('H:i:s')."] Done with {$server->name}");
        }

        $diff = microtime(true) - $start;
        $this->line("[".date('H:i:s')."] Finished all in {$diff} seconds");
    }

    private function decodeName($name)
    {
        $name = htmlspecialchars_decode($name);
        return trim(str_replace('&apos;', '\'', $name));
    }
}

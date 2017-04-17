<?php

namespace PRStats\Console\Commands;

use Illuminate\Console\Command;
use PRStats\Models\Clan;
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
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = microtime(true);
        $data  = file_get_contents('http://www.realitymod.com/prspy/json/serverdata.json');
        $diff  = microtime(true) - $start;
        $this->line("[".date('H:i:s')."] Downloaded prspy data in {$diff} seconds");
        $this->line("");
        $data    = json_decode(trim($data, '"'));
        $servers = isset($data->Data) ? $data->Data : [];

        $start = microtime(true);

        foreach ($servers as $serverData) {
            if ($serverData->Password || ($serverData->NumPlayers < 2) || $serverData->CoopEnabled == true) {
                continue;
            }

            $newgame    = false;
            $serverName = trim(preg_replace('/^\[.*?\]/is', '', $this->decodeName($serverData->ServerName), 1));

            $server = Server::where('name', $serverName)
                ->orWhere(function ($query) use ($serverData) {
                    $query->where('ip_address', $serverData->IPAddress)
                        ->where('game_port', $serverData->GamePort);
                })
                ->first();

            if ($server == null) {
                $server               = new Server;
                $server->ip_address   = $serverData->IPAddress;
                $server->game_port    = $serverData->GamePort;
                $server->games_played = 1;
                $newgame              = true;
                $server->save();
            }

            if ($server->last_map != $serverData->MapName) {
                $newgame          = true;
                $server->last_map = $serverData->MapName;
            }

            $server->name           = $serverName;
            $server->country        = $serverData->Country;
            $server->num_players    = $serverData->NumPlayers;
            $server->max_players    = $serverData->MaxPlayers;
            $server->reserved_slots = $serverData->ReservedSlots;
            $server->os             = $serverData->OS;

            if ($serverData->BattleRecorder) {
                $server->br_index    = $serverData->BRIndex;
                $server->br_download = $serverData->BRDownload;
            }

            $server->server_text       = $serverData->ServerText;
            $server->server_logo       = $serverData->ServerLogo;
            $server->community_website = $serverData->CommunityWebsite;

            if ($newgame) {
                $server->games_played++;
                $server->total_score += $serverData->Teams[0]->Score + $serverData->Teams[1]->Score;

            } else {
                $server->total_score += ($serverData->Teams[0]->Score - $server->team1_score)
                    + ($serverData->Teams[1]->Score - $server->team2_score);
            }

            //process numbers
            $server->team1_name   = $serverData->Teams[0]->Name;
            $server->team1_score  = $serverData->Teams[0]->Score;
            $server->team1_kills  = $serverData->Teams[0]->Kills;
            $server->team1_deaths = $serverData->Teams[0]->Deaths;

            $server->team2_name   = $serverData->Teams[1]->Name;
            $server->team2_score  = $serverData->Teams[1]->Score;
            $server->team2_kills  = $serverData->Teams[1]->Kills;
            $server->team2_deaths = $serverData->Teams[1]->Deaths;

            //process players & clans
            foreach ($serverData->Players as $playerData) {
                $player = Player::where('pid', $playerData->Pid)->first();
                if ($player == null) {
                    $player               = new Player;
                    $player->pid          = $playerData->Pid;
                    $player->games_played = 1;
                }

                $hasClan = (strpos($playerData->Name, ' ') !== false);
                $name    = $this->decodeName($playerData->Name);

                if ($hasClan) {
                    $parts   = explode(' ', $playerData->Name);
                    $clanTag = $parts[0];
                    $name    = $parts[1];

                    $clan = Clan::where('name', $this->decodeName($clanTag))->first();
                    if ($clan == null) {
                        $clan       = new Clan;
                        $clan->name = $this->decodeName($clanTag);
                        $clan->slug = str_slug($clan->name);
                        $clan->save();
                    }
                    $player->clan_id = $clan->id;
                } else {
                    $player->clan_id = null;
                }

                $player->name = $this->decodeName($name);
                $player->slug = str_slug($player->name);

                $player->total_score  = ($player->last_score > $playerData->Score) ?
                    $player->total_score + $playerData->Score :
                    $player->total_score + $playerData->Score - $player->last_score;
                $player->total_kills  = ($player->last_kills > $playerData->Kills) ?
                    $player->total_kills + $playerData->Kills :
                    $player->total_kills + $playerData->Kills - $player->last_kills;
                $player->total_deaths = ($player->last_deaths > $playerData->Deaths) ?
                    $player->total_deaths + $playerData->Deaths :
                    $player->total_deaths + $playerData->Deaths - $player->last_deaths;

                $player->monthly_score  = ($player->last_score > $playerData->Score) ?
                    $player->monthly_score + $playerData->Score :
                    $player->monthly_score + $playerData->Score - $player->last_score;
                $player->monthly_kills  = ($player->last_kills > $playerData->Kills) ?
                    $player->monthly_kills + $playerData->Kills :
                    $player->monthly_kills + $playerData->Kills - $player->last_kills;
                $player->monthly_deaths = ($player->last_deaths > $playerData->Deaths) ?
                    $player->monthly_deaths + $playerData->Deaths :
                    $player->monthly_deaths + $playerData->Deaths - $player->last_deaths;


                $player->games_played = ($player->last_score > $playerData->Score) ? $player->games_played + 1 : (int)$player->games_played;

                $server->total_score  = ($player->last_score > $playerData->Score) ?
                    $server->total_score + $playerData->Score :
                    $server->total_score + $playerData->Score - $player->last_score;
                $server->total_kills  = ($player->last_kills > $playerData->Kills) ?
                    $server->total_kills + $playerData->Kills :
                    $server->total_kills + $playerData->Kills - $player->last_kills;
                $server->total_deaths = ($player->last_deaths > $playerData->Deaths) ?
                    $server->total_deaths + $playerData->Deaths :
                    $server->total_deaths + $playerData->Deaths - $player->last_deaths;


                $player->last_score  = $playerData->Score;
                $player->last_kills  = $playerData->Kills;
                $player->last_deaths = $playerData->Deaths;
                $minutes             = (int)$player->minutes_played;
                $minutes++;
                $player->minutes_played = $minutes;

                $player->server_id = $server->id;
                $player->save();
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
        return str_replace('&apos;', '\'', $name);
    }
}

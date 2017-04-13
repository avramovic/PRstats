<?php

namespace PRStats\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use PRStats\Models\Clan;

class UpdateClans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prspy:clans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update squad data';

    /**
     * Create a new command instance.
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
        //get clans
        $this->line(Carbon::now());
        $clans = Clan::with('players')->orderBy('updated_at', 'asc')->take(100)->get();

//        dd($clans->toJson());

        foreach ($clans as $clan) {
            $total_score  = 0;
            $total_kills  = 0;
            $total_deaths = 0;
            foreach ($clan->players as $player) {
                $total_score += $player->total_score;
                $total_kills += $player->total_kills;
                $total_deaths += $player->total_deaths;
            }

            $clan->total_score  = $total_score;
            $clan->total_kills  = $total_kills;
            $clan->total_deaths = $total_deaths;
            $clan->updated_at   = Carbon::now();
            $clan->save();

            $this->line('Done with '.$clan->name);
        }
    }
}

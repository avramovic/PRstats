<?php

namespace PRStats\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use PRStats\Models\Player;

class SyncPlayerHoursCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:playtime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync player hours command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $total = Player::count();
        Player::with('matches')
            ->orderBy('id')->chunk(10, function (Collection $players) use ($total) {
                /** @var Player $player */
                foreach ($players as $player) {
                    $player->timestamps     = false;
                    $player->minutes_played = $player->minutesPlayed();
                    $player->save();
                    $this->line(sprintf('Updated %d out of %d players', $player->id, $total));
                }
                sleep(1);
            });
        $this->info('Done.');
    }
}

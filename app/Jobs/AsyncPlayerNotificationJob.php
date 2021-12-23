<?php

namespace PRStats\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PRStats\Models\Match;
use PRStats\Models\Player;
use PRStats\Notifications\PlayerActivityWebNotification;

class AsyncPlayerNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Player
     */
    private $player;
    /**
     * @var Match
     */
    private $match;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Player $player, Match $match)
    {
        $this->player = $player;
        $this->match = $match;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->player->notify(new PlayerActivityWebNotification($this->match));
    }
}

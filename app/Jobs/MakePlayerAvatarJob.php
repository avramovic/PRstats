<?php

namespace PRStats\Jobs;

use Avram\Robohash\Robohash;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PRStats\Models\Player;

class MakePlayerAvatarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Player
     */
    private $player;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $avatar = Robohash::make($this->player->pid, 140, 'set5');

        Storage::put($this->player->getAvatarPath(), $avatar->encode('png'));
    }
}

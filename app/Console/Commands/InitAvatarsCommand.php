<?php

namespace PRStats\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PRStats\Jobs\MakePlayerAvatarJob;
use PRStats\Models\Player;

class InitAvatarsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:avatars {{date=today}}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate avatars';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $before = Carbon::parse($this->argument('date'))->startOfDay();
        } catch (\Exception $e) {
            $before = Carbon::now()->startOfDay();
        }

        Player::where('created_at', '<=', $before)
            ->orderBy('id')
            ->chunk(10, function ($players) {
                /** @var Player $player */
                foreach ($players as $player) {
                    if (!Storage::exists($player->getAvatarPath())) {
                        dispatch(new MakePlayerAvatarJob($player));
                    }
                }
                sleep(1);
            });
    }
}

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

        $total     = Player::count();
        $processed = 0;

        Player::where('created_at', '<=', $before)
            ->orderBy('id')
            ->chunk(10, function ($players) use ($total, $processed) {
                /** @var Player $player */
                foreach ($players as $player) {
                    if (!Storage::exists($player->getAvatarPath())) {
                        dispatch(new MakePlayerAvatarJob($player));
                    }
                    $processed++;
                }
                $this->line(sprintf('Processed %d out of %d', $processed, $total));
                sleep(1);
            });

        $this->info('Done');
    }
}

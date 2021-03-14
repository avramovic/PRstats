<?php

namespace PRStats\Console\Commands;

use Illuminate\Console\Command;
use PRStats\Jobs\MakePlayerSignatureJob;
use PRStats\Models\Player;

class MakeSignatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:signature {player}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make player signature';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $player = Player::findOrFail($this->argument('player'));
        with(new MakePlayerSignatureJob($player))->handle();
        $this->info('Created:');
        $this->line('https://static.prstats.tk/'.$player->getSignaturePath());
    }
}

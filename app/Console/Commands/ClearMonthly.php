<?php

namespace PRStats\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PRStats\Models\Player;


class ClearMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prspy:clearmonthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear monthly scores';

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

        $affected = DB::table('players')->update([
            'monthly_score' => 0,
            'monthly_kills' => 0,
            'monthly_deaths' => 0,
        ]);


        $diff = microtime(true) - $start;
        $this->line("[".date('H:i:s')."] Updated {$affected} players in {$diff} seconds");
    }
}

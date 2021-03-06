<?php

namespace PRStats\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
         Commands\PRSpyParse::class,
         Commands\UpdateClans::class,
         Commands\ClearMonthly::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('prspy:parse')->everyMinute();
        $schedule->command('prspy:clans')->everyFiveMinutes();
        $schedule->command('prspy:clearmonthly')->monthly();
        $schedule->command('sync:playtime')->monthlyOn(1, '9:00');
        $schedule->command('cache:clear')->weeklyOn(3);
    }

    public function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}

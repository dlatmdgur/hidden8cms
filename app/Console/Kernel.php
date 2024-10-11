<?php

namespace App\Console;

use App\Console\Commands\GatherBillingStatistics;
use App\Console\Commands\GatherBuyItemStatistics;
use App\Console\Commands\GatherGoodsStatistics;
use App\Console\Commands\MakePassword;
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
        GatherBillingStatistics::class,
        GatherBuyItemStatistics::class,
        GatherGoodsStatistics::class,
        MakePassword::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

<?php

namespace App\Console;

use App\Console\Commands\ConsumerCommand;
use App\Console\Commands\DeleteMarkedGoodsCommand;
use App\Console\Commands\IndexMarkedGoodsCommand;
use App\Console\Commands\MigrateOptionsCommand;
use App\Console\Commands\MigrateOptionValuesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ConsumerCommand::class,
        IndexMarkedGoodsCommand::class,
        DeleteMarkedGoodsCommand::class,
        MigrateOptionsCommand::class,
        MigrateOptionValuesCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:index-marked-goods')->everyFifteenMinutes()->runInBackground();
        $schedule->command('db:delete-marked-goods')->hourly();
    }
}

<?php

namespace App\Console;

use App\Console\Commands\ConsumerCommand;
use App\Console\Commands\DeleteMarkedGoodsCommand;
use App\Console\Commands\IndexGoodsConstructors;
use App\Console\Commands\IndexMarkedGoodsCommand;
use App\Console\Commands\MigrateGoodsCommand;
use App\Console\Commands\MigrateOptionsCommand;
use App\Console\Commands\MigrateOptionValuesCommand;
use App\Console\Commands\MigrateProducersCommand;
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
        IndexGoodsConstructors::class,
        MigrateProducersCommand::class,
        MigrateGoodsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:migrate-goods')->everyFifteenMinutes()->runInBackground();
        $schedule->command('db:index-marked-goods')->everyFiveMinutes()->runInBackground();
        $schedule->command('db:index-goods-constructors')->everyFiveMinutes();

        $schedule->command('db:delete-marked-goods')->hourly();
    }
}

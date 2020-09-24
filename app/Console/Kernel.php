<?php

namespace App\Console;

use App\Console\Commands\ConsumerCommand;
use App\Console\Commands\DeleteConstructorsCommand;
use App\Console\Commands\DeleteGoodsConstructorsCommand;
use App\Console\Commands\DeleteGroupsConstructorsCommand;
use App\Console\Commands\DeleteMarkedGoodsCommand;
use App\Console\Commands\IndexGoodsConstructors;
use App\Console\Commands\IndexGoodsGroupsConstructors;
use App\Console\Commands\IndexMarkedGoodsCommand;
use App\Console\Commands\MigrateGoodsCommand;
use App\Console\Commands\MigrateGoodsGroupsCommand;
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
        IndexGoodsGroupsConstructors::class,
        MigrateGoodsGroupsCommand::class,
        DeleteGoodsConstructorsCommand::class,
        DeleteGroupsConstructorsCommand::class,
        DeleteConstructorsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:migrate-goods')->everyFiveMinutes()->runInBackground();
        $schedule->command('db:migrate-goods-groups')->everyFiveMinutes()->runInBackground();

        $schedule->command('db:index-marked-goods')->everyFiveMinutes()->runInBackground();
        $schedule->command('db:index-goods-constructors')->everyFiveMinutes();
        $schedule->command('db:index-goods-groups-constructors')->everyFiveMinutes();

        $schedule->command('db:delete-marked-goods')->hourly();
        $schedule->command('db:delete-constructors')->everyFifteenMinutes();
        $schedule->command('db:delete-groups-constructors')->everyFifteenMinutes();
        $schedule->command('db:delete-goods-constructors')->everyFifteenMinutes();
    }
}

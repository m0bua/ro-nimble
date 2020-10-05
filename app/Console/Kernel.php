<?php

namespace App\Console;

use App\Console\Commands\ConsumerCommand;
use App\Console\Commands\DeleteConstructorsCommand;
use App\Console\Commands\DeleteGoodsConstructorsCommand;
use App\Console\Commands\DeleteGroupsConstructorsCommand;
use App\Console\Commands\DeleteMarkedGoodsCommand;
use App\Console\Commands\IndexGoodsConstructors;
use App\Console\Commands\IndexGoodsGroupsConstructors;
use App\Console\Commands\IndexGoodsOptionsCommand;
use App\Console\Commands\IndexGoodsOptionsPluralCommand;
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
        IndexGoodsOptionsCommand::class,
        IndexGoodsOptionsPluralCommand::class,
        MigrateProducersCommand::class,
        MigrateGoodsCommand::class,
        IndexGoodsGroupsConstructors::class,
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
        $schedule->command('db:migrate-goods')->runInBackground()->withoutOverlapping();
        $schedule->command('db:migrate-goods --entity=groups')->runInBackground()->withoutOverlapping();

        $schedule->command('db:index-marked-goods')->runInBackground()->withoutOverlapping();
        $schedule->command('db:index-goods-options')->runInBackground()->withoutOverlapping();
        $schedule->command('db:index-goods-options-plural')->runInBackground()->withoutOverlapping();
        $schedule->command('db:index-goods-constructors')->runInBackground()->withoutOverlapping();
        $schedule->command('db:index-goods-groups-constructors')->runInBackground()->withoutOverlapping();

        $schedule->command('db:delete-marked-goods')->runInBackground()->withoutOverlapping();
        $schedule->command('db:delete-constructors')->runInBackground()->withoutOverlapping();
        $schedule->command('db:delete-groups-constructors')->runInBackground()->withoutOverlapping();
        $schedule->command('db:delete-goods-constructors')->runInBackground()->withoutOverlapping();
    }
}

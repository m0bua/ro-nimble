<?php

namespace App\Console;

use App\Console\Commands\ConsumerCommand;
use App\Console\Commands\DeleteConstructorsCommand;
use App\Console\Commands\DeleteGoodsConstructorsCommand;
use App\Console\Commands\DeleteGroupsConstructorsCommand;
use App\Console\Commands\DeleteMarkedGoodsCommand;
use App\Console\Commands\Dev;
use App\Console\Commands\IndexGoodsConstructors;
use App\Console\Commands\IndexGoodsGroupsConstructors;
use App\Console\Commands\IndexGoodsOptionsCommand;
use App\Console\Commands\IndexGoodsOptionsPluralCommand;
use App\Console\Commands\IndexGoodsProducersCommand;
use App\Console\Commands\IndexMarkedGoodsCommand;
use App\Console\Commands\IndexProducers;
use App\Console\Commands\MigrateGoodsCommand;
use App\Console\Commands\MigrateOptionsCommand;
use App\Console\Commands\MigrateOptionValuesCommand;
use App\Console\Commands\MigrateProducersCommand;
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
        ConsumerCommand::class,
        IndexMarkedGoodsCommand::class,
        DeleteMarkedGoodsCommand::class,
        MigrateOptionsCommand::class,
        MigrateOptionValuesCommand::class,
        IndexGoodsConstructors::class,
        IndexGoodsOptionsCommand::class,
        IndexGoodsOptionsPluralCommand::class,
        IndexGoodsProducersCommand::class,
        MigrateProducersCommand::class,
        MigrateGoodsCommand::class,
        IndexGoodsGroupsConstructors::class,
        DeleteGoodsConstructorsCommand::class,
        DeleteGroupsConstructorsCommand::class,
        DeleteConstructorsCommand::class,
        IndexProducers::class,

        Dev\TestCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(MigrateGoodsCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(MigrateGoodsCommand::class, ['--entity' => 'groups'])->runInBackground()->withoutOverlapping();

        $schedule->command(IndexMarkedGoodsCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(IndexGoodsOptionsCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(IndexGoodsOptionsPluralCommand::class)->runInBackground()->withoutOverlapping();

        $schedule->command(IndexGoodsProducersCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(IndexProducers::class)->hourlyAt(10)->runInBackground()->withoutOverlapping();

        $schedule->command(DeleteMarkedGoodsCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(DeleteConstructorsCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(DeleteGroupsConstructorsCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(DeleteGoodsConstructorsCommand::class)->runInBackground()->withoutOverlapping();

        $schedule->command(IndexGoodsConstructors::class)->withoutOverlapping();
        $schedule->command(IndexGoodsGroupsConstructors::class)->withoutOverlapping();
    }
}

<?php

namespace App\Console;

use App\Console\Commands\Delete;

use App\Console\Commands\DeleteConstructorsCommand;
use App\Console\Commands\DeleteGoodsConstructorsCommand;
use App\Console\Commands\DeleteGroupsConstructorsCommand;
use App\Console\Commands\DeleteMarkedGoodsCommand;

use App\Console\Commands\Dev;
use App\Console\Commands\FillLostTranslations;
use App\Console\Commands\Index;
use App\Console\Commands\IndexGoodsConstructors;
use App\Console\Commands\Migrate;
use App\Console\Commands\MigrateGoodsCommand;
use App\Console\Commands\MigrateOptionsCommand;
use App\Console\Commands\MigrateOptionValuesCommand;
use App\Console\Commands\MigrateProducersCommand;
use App\Console\Commands\StartConsumer;
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
        StartConsumer::class,
        FillLostTranslations::class,

        Index\IndexGoodsConstructors::class,
        Index\IndexGoodsGroupsConstructors::class,
        Index\IndexGoodsOptions::class,
        Index\IndexGoodsOptionsPlural::class,
        Index\IndexGoodsProducers::class,
        Index\IndexMarkedGoods::class,
        Index\IndexProducers::class,

//        Delete\DeleteConstructors::class,
//        Delete\DeleteGoodsConstructors::class,
//        Delete\DeleteGroupsConstructors::class,
//        Delete\DeleteMarkedGoods::class,

        DeleteConstructorsCommand::class,
        DeleteGoodsConstructorsCommand::class,
        DeleteGroupsConstructorsCommand::class,
        DeleteMarkedGoodsCommand::class,

        Migrate\MigrateGoods::class,
//        Migrate\MigrateOptions::class,
//        Migrate\MigrateOptionValues::class,
//        Migrate\MigrateProducers::class,

//        MigrateGoodsCommand::class,
        MigrateOptionsCommand::class,
        MigrateOptionValuesCommand::class,
        MigrateProducersCommand::class,

        Dev\TestCommand::class,
        Dev\GenerateModelMeta::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(FillLostTranslations::class)->runInBackground()->withoutOverlapping();

        $schedule->command(Migrate\MigrateGoods::class)->runInBackground()->withoutOverlapping();
        $schedule->command(Migrate\MigrateGoods::class, ['--entity' => 'groups'])->runInBackground()->withoutOverlapping();

        $schedule->command(Index\IndexMarkedGoods::class)->runInBackground()->withoutOverlapping();
        $schedule->command(Index\IndexGoodsOptions::class)->runInBackground()->withoutOverlapping();
        $schedule->command(Index\IndexGoodsOptionsPlural::class)->runInBackground()->withoutOverlapping();
        $schedule->command(Index\IndexGoodsProducers::class)->runInBackground()->withoutOverlapping();
        $schedule->command(Index\IndexProducers::class)->hourlyAt(10)->runInBackground()->withoutOverlapping();

        $schedule->command(DeleteConstructorsCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(DeleteGoodsConstructorsCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(DeleteGroupsConstructorsCommand::class)->runInBackground()->withoutOverlapping();
        $schedule->command(DeleteMarkedGoodsCommand::class)->runInBackground()->withoutOverlapping();

        $schedule->command(Index\IndexGoodsConstructors::class)->runInBackground()->withoutOverlapping();
        $schedule->command(Index\IndexGoodsGroupsConstructors::class)->runInBackground()->withoutOverlapping();
    }
}

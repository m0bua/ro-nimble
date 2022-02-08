<?php

namespace App\Console;

use App\Console\Commands\Delete;
use App\Console\Commands\Dev;
use App\Console\Commands\FillLostTranslations;
use App\Console\Commands\IndexingConsumer;
use App\Console\Commands\IndexProducers;
use App\Console\Commands\IndexRefill;
use App\Console\Commands\IndexServices;
use App\Console\Commands\PartialIndexing;
use App\Console\Commands\StartConsumer;
use App\Console\Commands\Precount\FillPrecountOptionSettings;
use App\Console\Commands\Precount\FillPrecountOptionSliders;

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
        FillLostTranslations::class,

        Delete\DeleteConstructors::class,
        Delete\DeleteGoodsConstructors::class,
        Delete\DeleteGroupsConstructors::class,
        Delete\DeleteMarkedGoods::class,

        StartConsumer::class,
        IndexingConsumer::class,
        IndexRefill::class,
        Dev\TestCommand::class,
        Dev\GenerateModelMeta::class,

        IndexProducers::class,

        FillPrecountOptionSettings::class,
        FillPrecountOptionSliders::class,

        PartialIndexing::class,
        IndexServices::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(FillLostTranslations::class)->runInBackground()->withoutOverlapping();
//        $schedule->command(FillPrecountOptionSettings::class)->runInBackground()->hourly();
        $schedule->command(FillPrecountOptionSliders::class)->runInBackground()->hourly();

        $schedule->command(IndexProducers::class)->dailyAt('00:00');
        $schedule->command(IndexRefill::class)->dailyAt('02:00');
        $schedule->command(IndexServices::class);
        $schedule->command(PartialIndexing::class)->runInBackground()->withoutOverlapping();

        $schedule->command(Delete\DeleteMarkedGoods::class);
    }


}

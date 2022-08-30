<?php

namespace App\Console;

use App\Console\Commands\Delete;
use App\Console\Commands\Dev;
use App\Console\Commands\FetchFiltersAutoranking;
use App\Console\Commands\FillLostTranslations;
use App\Console\Commands\Indexing;
use App\Console\Commands\IndexProducers;
use App\Console\Commands\Precount\FillPrecountOptionSettings;
use App\Console\Commands\Precount\FillPrecountOptionSliders;
use App\Console\Commands\Seed\{
    Seed,
    GenerateSeed
};
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
        FillLostTranslations::class,

        Delete\DeleteMarkedGoods::class,

        StartConsumer::class,
        Dev\TestCommand::class,
        Dev\GenerateModelMeta::class,

        IndexProducers::class,

        FillPrecountOptionSettings::class,
        FillPrecountOptionSliders::class,

        Indexing\Consumer::class,
        Indexing\Publish::class,
        Indexing\Partial::class,
        Indexing\Services::class,

        FetchFiltersAutoranking::class,

        GenerateSeed::class,
        Seed::class,
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
        $schedule->command(FillPrecountOptionSettings::class)->runInBackground()->dailyAt('22:00');
        $schedule->command(FillPrecountOptionSliders::class)->runInBackground()->dailyAt('21:00');

        $schedule->command(IndexProducers::class)->dailyAt('00:00');
        $schedule->command(Indexing\Publish::class)->dailyAt('02:00');
        $schedule->command(Indexing\Services::class);
        $schedule->command(Indexing\Partial::class)->runInBackground()->withoutOverlapping();

        $schedule->command(Delete\DeleteMarkedGoods::class);

        $schedule->command(FetchFiltersAutoranking::class)->dailyAt('10:30');
    }
}

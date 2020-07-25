<?php

namespace App\Console;

use App\Console\Commands\{ChangePromotionConstructorGoodsCommand,
    ChangePromotionConstructorGroupsCommand,
    ChangePromotionConstructorCommand,
    ConsumePromotionGoodsCommand,
    DeletePromotionConstructorCommand,
    DeletePromotionConstructorGoodsCommand,
    DeletePromotionConstructorGroupsCommand};
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
        ConsumePromotionGoodsCommand::class,
//        ChangePromotionConstructorCommand::class,
//        ChangePromotionConstructorGoodsCommand::class,
//        ChangePromotionConstructorGroupsCommand::class,
//        DeletePromotionConstructorCommand::class,
//        DeletePromotionConstructorGoodsCommand::class,
//        DeletePromotionConstructorGroupsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}

<?php

namespace App\Console\Commands\Dev;

use App\Services\Elastic\GoodsRangeDataFormatter;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for testing everything';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        app()->make(GoodsRangeDataFormatter::class)->collect([1,2,3,4,5,6,7,8,9,10]);
        return 0;
    }
}

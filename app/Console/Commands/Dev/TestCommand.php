<?php

namespace App\Console\Commands\Dev;

use App\Models\Eloquent\Option;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info((new Option())->write()->getConnectionName());

        $model = (new Option())->write()->create([
            'id' => rand(1, 100000),
            'affect_group_photo' => false,
        ]);

        $this->info(print_r($model->toArray(), true));
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class MarkForDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:mark-for-delete {--tables=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marks all records in tables (`need_delete = 1`';

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
    public function handle()
    {
        $tables = $this->option('tables');
        foreach ($tables as $table) {
            $this->getOutput()->writeln("<fg=yellow>Marking:</> $table");
            try {
                DB::table($table)->select('id')->chunkById(1000, function($items) use ($table) {
                    DB::table($table)->whereIn('id', $items->pluck('id'))->update(['need_delete' => 1]);
                });
                $this->getOutput()->writeln("<fg=green>Marked:</> $table");
            } catch (Throwable $t) {
                $this->getOutput()->writeln("<fg=red>Error:</> $table");
            }
        }
        return 0;
    }
}

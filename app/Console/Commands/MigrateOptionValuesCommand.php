<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\Chunks\ChunkCursor;
use Illuminate\Support\Facades\DB;

class MigrateOptionValuesCommand extends CustomCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:migrate-options-values';

    /**
     * @var string
     */
    protected $description = 'Migrate options values from db store to db nimble';

    /**
     *
     */
    public function handle()
    {
        $this->catchExceptions(function () {

            $optValQuery = DB::connection('store')
                ->table('options_values');

            ChunkCursor::iterate($optValQuery, function ($optValues) {
                $valuesArray = [];
                array_map(function ($value) use (&$valuesArray) {
                    $valuesArray[] = (array)$value;
                }, $optValues);

                DB::table('option_values')->insertOrIgnore($valuesArray);
            });

        });
    }

}

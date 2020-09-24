<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\QueryBuilderHelper;
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

            QueryBuilderHelper::chunk(500, $optValQuery, function ($optValues) {
                $valuesArray = [];
                array_map(function ($value) use (&$valuesArray) {
                    $valuesArray[] = (array)$value;
                }, $optValues->toArray());

                DB::table('option_values')->insertOrIgnore($valuesArray);
            });

        });
    }

}

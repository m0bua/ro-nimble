<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\QueryBuilderHelper;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MigrateOptionsCommand extends CustomCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:migrate-options';

    /**
     * @var string
     */
    protected $description = 'Migrate options from db store to db nimble';

    /**
     *
     */
    public function handle()
    {
        $this->catchExceptions(function () {

            $optQuery = DB::connection('store')
                ->table('options');

            QueryBuilderHelper::chunk($optQuery, function ($options) {
                $optionsArray = [];

                array_map(function ($option) use (&$optionsArray){
                    $opt = (array)$option;
                    $opt['affect_group_photo'] = ($opt['affect_group_photo'] ? 't' : 'f');
                    unset($opt['copy_forbid']);
                    return $optionsArray[] = $opt;
                }, $options);

                DB::table('options')->insertOrIgnore($optionsArray);
            });

        });
    }
}

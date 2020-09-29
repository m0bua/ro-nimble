<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\QueryBuilderHelper;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MigrateProducersCommand extends CustomCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:migrate-producers';

    /**
     * @var string
     */
    protected $description = 'Migrate producers from db store to db nimble';

    /**
     *
     */
    public function handle()
    {
        $this->catchExceptions(function () {

            $producersQuery = DB::connection('store')
                ->table('producers');

            QueryBuilderHelper::chunk($producersQuery, function ($producers) {
                $producersArray = [];

                array_map(function ($producer) use (&$producersArray) {
                    $data = (array)$producer;
                    $data['disable_filter_series'] = ($data['disable_filter_series'] ? 't' : 'f');
                    $data['show_background'] = ($data['show_background'] ? 't' : 'f');
                    unset($data['attachments']);
                    $producersArray[] = $data;
                }, $producers);

                DB::table('producers')->insertOrIgnore($producersArray);
            });

        }, true);
    }
}

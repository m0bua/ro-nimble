<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\ExtCommand;
use App\Helpers\Chunks\ChunkCursor;
use Illuminate\Support\Facades\DB;

class MigrateProducersCommand extends ExtCommand
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
    protected function extHandle()
    {
        $producersQuery = DB::connection('store')
            ->table('producers');

        ChunkCursor::iterate($producersQuery, function ($producers) {
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
    }
}
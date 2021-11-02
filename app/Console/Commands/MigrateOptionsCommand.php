<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\ExtCommand;
use App\Helpers\Chunks\ChunkCursor;
use Illuminate\Support\Facades\DB;

class MigrateOptionsCommand extends ExtCommand
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
    protected function extHandle()
    {
        $optQuery = DB::connection('store')
            ->table('options');

        ChunkCursor::iterate($optQuery, function ($options) {
            $optionsArray = [];

            array_map(function ($option) use (&$optionsArray){
                $opt = (array)$option;
                $opt['affect_group_photo'] = ($opt['affect_group_photo'] ? 't' : 'f');
                unset($opt['copy_forbid']);
                return $optionsArray[] = $opt;
            }, $options);

            DB::table('options')->insertOrIgnore($optionsArray);
        });
    }
}

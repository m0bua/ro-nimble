<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\QueryBuilderHelper;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MigrateGoodsCommand extends CustomCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:migrate-goods';

    /**
     * @var string
     */
    protected $description = 'Migrate goods from db store to db nimble';

    /**
     *
     */
    public function handle()
    {
        $this->catchExceptions(function () {

            $query = DB::connection('store')
                ->table('goods')
                ->select([
                    'goods.id',
                    'title',
                    'name',
                    'category_id',
                    'mpath',
                    'price',
                    'gr.search_rank as rank',
                    'sell_status',
                    'producer_id',
                    'seller_id',
                    'group_id',
                    'is_group_primary',
                    'status_inherited',
                    'order',
                    'series_id',
                    'state',
                    'is_deleted',
                    'created_at',
                    'updated_at',
                ])
                ->leftJoin('goods_ranks as gr', 'goods.id', '=', 'gr.id')
                ->latest('changed');

            QueryBuilderHelper::chunk(500, $query, function ($goods) {
                $dataArray = [];

                array_map(function ($product) use (&$dataArray) {
                    $data = (array)$product;

                    $dataArray[] = $data;
                }, $goods->toArray());

                DB::table('goods')->insertOrIgnore($dataArray);
            });

        }, true);
    }
}

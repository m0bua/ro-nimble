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

            $promoGoodsQuery = DB::table('promotion_goods_constructors')
                ->select(['goods_id'])
                ->where(['needs_index' => 1]);

            QueryBuilderHelper::chunk(500, $promoGoodsQuery, function ($data) {
                $goodsIds = [];
                $data->map(function ($item) use (&$goodsIds) {
                    $goodsIds[] = $item->goods_id;
                });

                $goods = DB::connection('store')
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
                    ->whereIn('goods.id', $goodsIds)
                    ->get();


                $dataArray = [];
                array_map(function ($product) use (&$dataArray) {
                    $dataArray[] = (array)$product;
                }, $goods->toArray());

                DB::table('goods')->insertOrIgnore($dataArray);
                DB::table('promotion_goods_constructors')
                    ->whereIn('goods_id', $goodsIds)
                    ->update(['needs_index' => 0]);
            });

        });
    }
}

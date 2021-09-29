<?php

namespace App\Console\Commands\Index;

use App\Console\Commands\Command;
use App\Helpers\Chunks\ChunkCursor;
use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Facades\DB;
use Throwable;

class IndexMarkedGoods extends Command
{
    /**
     * @var string
     */
    protected $signature = 'db:index-marked-goods';

    /**
     * @var string
     */
    protected $description = 'Fill index goods table';

    /**
     *
     */
    protected const GOODS_COUNT_LIMIT = 500;

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elasticGoods;

    /**
     * IndexMarkedGoodsCommand constructor.
     * @param GoodsModel $elasticGoods
     */
    public function __construct(GoodsModel $elasticGoods)
    {
        $this->elasticGoods = $elasticGoods;

        parent::__construct();
    }

    /**
     * @inerhitDoc
     * @throws Throwable
     */
    protected function proceed(): void
    {
        $goodsQuery = DB::table('goods')
            ->select([
                'goods.*',
                'producers.name as producer_name',
                'producers.title as producer_title',
            ])
            ->leftJoin('producers', 'producers.id', '=', 'goods.producer_id')
            ->where(['goods.needs_index' => 1]);

        ChunkCursor::iterate($goodsQuery, function ($products) {
            $productsData = [];
            array_map(function ($product) use (&$productsData) {
                /**
                 * Common info
                 */
                $productsData[$product->id]['id'] = $product->id;
                $productsData[$product->id]['promotion_constructors'] = [];
                $productsData[$product->id]['category_id'] = $product->category_id;
                $productsData[$product->id]['mpath'] = $product->mpath;
                $productsData[$product->id]['price'] = $product->price;
                $productsData[$product->id]['sell_status'] = $product->sell_status;
                $productsData[$product->id]['producer_id'] = $product->producer_id;
                $productsData[$product->id]['seller_id'] = $product->seller_id;
                $productsData[$product->id]['group_id'] = $product->group_id;
                $productsData[$product->id]['is_group_primary'] = $product->is_group_primary;
                $productsData[$product->id]['status_inherited'] = $product->status_inherited;
                $productsData[$product->id]['order'] = $product->order;
                $productsData[$product->id]['state'] = $product->state;
                $productsData[$product->id]['rank'] = $product->rank;
                $productsData[$product->id]['producer_id'] = $product->producer_id;
                $productsData[$product->id]['producer_name'] = $product->producer_name;
                $productsData[$product->id]['producer_title'] = $product->producer_title;
                $productsData[$product->id]['options'] = [];
            }, $products);

            /**
             * Options info
             */
            $options = DB::table('options')
                ->select([
                    'options.id',
                    'options.name',
                    'options.type',
                    'options.state',
                    'go.value as value',
                    'go.goods_id'
                ])
                ->join('goods_options as go', 'go.option_id', '=', 'options.id')
                ->where('go.type', '!=', 'unknown')
                ->whereIn('go.goods_id', array_keys($productsData))
                ->get();

            $options->map(function ($option) use (&$productsData) {
                $productsData[$option->goods_id]['options'][$option->id]['details'] = [
                    'id' => $option->id,
                    'name' => $option->name,
                    'type' => $option->type,
                    'state' => $option->state,
                ];
                $productsData[$option->goods_id]['options'][$option->id]['value'] = $option->value;
            });

            /**
             * Options plural info
             */
            $optionsPlural = DB::table('options')
                ->select([
                    'options.id',
                    'options.name',
                    'options.type',
                    'options.state',
                    'gop.goods_id',
                    'ov.id as value_id',
                    'ov.name as value_name',
                    'ov.status as value_status',
                ])
                ->join('goods_options_plural as gop', 'gop.option_id', '=', 'options.id')
                ->join('option_values as ov', 'gop.value_id', '=', 'ov.id')
                ->whereIn('gop.goods_id', array_keys($productsData))
                ->get();

            $optionsPlural->map(function ($option) use (&$productsData) {
                $productsData[$option->goods_id]['options'][$option->id]['details'] = [
                    'id' => $option->id,
                    'name' => $option->name,
                    'type' => $option->type,
                    'state' => $option->state,
                ];
                $productsData[$option->goods_id]['options'][$option->id]['values'][] = [
                    'id' => $option->value_id,
                    'name' => $option->value_name,
                    'status' => $option->value_status,
                ];
            });

            $goodsConstructors = DB::table('promotion_goods_constructors as pgs')
                ->select([
                    'pgs.goods_id',
                    'pc.id',
                    'pc.promotion_id',
                    'pc.gift_id',
                ])
                ->join('promotion_constructors as pc', 'pgs.constructor_id', '=', 'pc.id')
                ->where(['pgs.is_deleted' => 0])
                ->whereIn('pgs.goods_id', array_keys($productsData))
                ->get();

            $goodsConstructors->map(function ($constructor) use (&$productsData) {
                $productsData[$constructor->goods_id]['promotion_constructors'][] = [
                    'gift_id' => $constructor->gift_id,
                    'promotion_id' => $constructor->promotion_id,
                    'id' => $constructor->id,
                ];
            });

            $groupsIds = array_values(array_filter(array_unique(array_column($productsData, 'group_id'))));
            $groupsConstructors = [];

            if ($groupsIds) {
                $groupsData = DB::table('promotion_groups_constructors as pgs')
                    ->select([
                        'pgs.group_id',
                        'pc.id',
                        'pc.promotion_id',
                        'pc.gift_id',
                    ])
                    ->join('promotion_constructors as pc', 'pgs.constructor_id', '=', 'pc.id')
                    ->where(['pgs.is_deleted' => 0])
                    ->whereIn('pgs.group_id', $groupsIds)
                    ->get();


                $groupsData->map(function ($constructor) use (&$groupsConstructors) {
                    $groupsConstructors[$constructor->group_id][] = [
                        'gift_id' => $constructor->gift_id,
                        'promotion_id' => $constructor->promotion_id,
                        'id' => $constructor->id,
                    ];
                });
            }

            /**
             * Format and index data
             */
            $formattedData = ['body' => []];
            array_map(function ($productData) use (&$formattedData, $groupsConstructors) {
                $formatter = new CommonFormatter($productData);
                $formatter->formatGoodsForIndex();
                $formatter->formatOptionsForIndex();
                $formatter->formatGroupsForIndex($groupsConstructors);

                $formattedData['body'][] = [
                    'update' => [
                        '_index' => $this->elasticGoods->indexName(),
                        '_id' => $productData['id'],
                    ],
                ];

                $formattedData['body'][] = [
                    'doc' => $formatter->getFormattedData(),
                    'doc_as_upsert' => true,
                ];
            }, $productsData);

            $this->elasticGoods->bulk($formattedData);

            /**
             * Mark goods as indexed
             */
            DB::table('goods')
                ->whereIn('id', array_keys($productsData))
                ->update(['needs_index' => 0]);
        });
    }
}

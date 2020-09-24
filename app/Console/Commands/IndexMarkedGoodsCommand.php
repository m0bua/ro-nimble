<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class IndexMarkedGoodsCommand extends CustomCommand
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
     *
     */
    public function handle()
    {
        $this->catchExceptions(function () {

            $goods = DB::table('goods')
                ->select([
                    'goods.*',
                    'producers.name as producer_name',
                    'producers.title as producer_title',
                ])
                ->leftJoin('producers', 'producers.id', '=', 'goods.producer_id')
                ->where(['goods.needs_index' => 1])
                ->latest()
                ->limit(self::GOODS_COUNT_LIMIT);

            do {
                $products = $goods->get();
                $countProducts = $products->count();

                if ($countProducts == 0) {
                    break;
                }

                $this->index($products);

                dump($countProducts);
            } while ($countProducts == self::GOODS_COUNT_LIMIT);
        });
    }

    private function index(Collection $products)
    {
        $productsData = [];
        $products->map(function ($product) use (&$productsData) {
            /**
             * Common info
             */
            $productsData[$product->id]['id'] = $product->id;
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
        });

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
            $productsData[$option->goods_id]['options'][$option->id]['value'] = $option->value;;
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

        /**
         * Format and index data
         */
        $formattedData = ['body' => []];
        array_map(function ($productData) use (&$formattedData) {
            $formatter = new CommonFormatter($productData);
            $formatter->formatGoodsForIndex();
            $formatter->formatOptionsForIndex();

            $formattedData['body'][] = [
                'index' => [
                    '_index' => $this->elasticGoods->indexName(),
                    '_id' => $productData['id'],
                ],
            ];
            $formattedData['body'][] = $formatter->getFormattedData();
        }, $productsData);

        $this->elasticGoods->bulk($formattedData);

        /**
         * Mark goods as indexed
         */
        DB::table('goods')
            ->whereIn('id', array_keys($productsData))
            ->update(['needs_index' => 0]);
    }
}

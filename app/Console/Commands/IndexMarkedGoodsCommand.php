<?php


namespace App\Console\Commands;


use App\Helpers\ArrayHelper;
use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class IndexMarkedGoodsCommand extends Command
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
        $goods = DB::table('goods')
            ->leftJoin('producers', 'goods.producer_id', '=', 'producers.id')
            ->leftJoin('goods_options as go', 'goods.id', '=', 'go.goods_id')
            ->leftJoin('goods_options_plural as gop', 'goods.id', '=', 'gop.goods_id')
            ->leftJoin('options as o', function($join) {
                $join->on('go.option_id', '=', 'o.id')
                     ->orOn('gop.option_id', '=', 'o.id');
            })
            ->leftJoin('option_values as ov', 'gop.value_id', '=', 'ov.id')
            ->select([
                'goods.*',
                'producers.title as producer_title',
                'producers.name as producer_name',
                'o.id as option_id',
                'o.name as option_name',
                'o.type as option_type',
                'o.state as option_state',
                'go.value as option_value',
                'ov.id as option_value_id',
                'ov.name as option_value_name',
                'ov.status as option_value_status',
            ])
            ->where(['goods.needs_index' => 1])
            ->latest('goods.updated_at')
            ->orderBy('goods.id')
            ->limit(self::GOODS_COUNT_LIMIT);

        do {
            $products = $goods->get();
            $countProducts = $products->count();

            if ($countProducts == 0) {
                break;
            }

            $this->index($products);

        } while ($countProducts == self::GOODS_COUNT_LIMIT);
    }

    /**
     * @param Collection $products
     */
    private function index(Collection $products)
    {
        $productData = [];
        $goodsIndexed = [];

        $products->map(function ($product) use (&$productData) {
            $productData[$product->id]['id'] = $product->id;
            $productData[$product->id]['category_id'] = $product->category_id;
            $productData[$product->id]['mpath'] = $product->mpath;
            $productData[$product->id]['price'] = $product->price;
            $productData[$product->id]['sell_status'] = $product->sell_status;
            $productData[$product->id]['producer_id'] = $product->producer_id;
            $productData[$product->id]['seller_id'] = $product->seller_id;
            $productData[$product->id]['group_id'] = $product->group_id;
            $productData[$product->id]['is_group_primary'] = $product->is_group_primary;
            $productData[$product->id]['status_inherited'] = $product->status_inherited;
            $productData[$product->id]['order'] = $product->order;
            $productData[$product->id]['state'] = $product->state;
            $productData[$product->id]['rank'] = $product->rank;
            $productData[$product->id]['producer_id'] = $product->producer_id;
            $productData[$product->id]['producer_name'] = $product->producer_name;
            $productData[$product->id]['producer_title'] = $product->producer_title;

            if (!is_null($product->option_id)) {
                $productData[$product->id]['options'][$product->option_id] = [
                    'details' => [
                        'id' => $product->option_id,
                        'name' => $product->option_name,
                        'type' => $product->option_type,
                        'state' => $product->option_state,
                    ],
                ];

                if (!is_null($product->option_value)) {
                    $productData[$product->id]['options'][$product->option_id]['value'] = $product->option_value;
                }

                if (!is_null($product->option_value_id)) {
                    $productData[$product->id]['options'][$product->option_id]['values'][] = [
                        'id' => $product->option_value_id,
                        'name' => $product->option_value_name,
                        'status' => $product->option_value_status,
                    ];
                }
            } else {
                $productData[$product->id]['options'] = [];
            }
        });

        array_map(function ($singleProduct) use (&$goodsIndexed) {
            $formatter = new CommonFormatter($singleProduct);
            $formatter->formatGoodsForIndex();
            $formatter->formatOptionsForIndex();
            $formattedData = $formatter->getFormattedData();

            $currentData = $this->elasticGoods->one(
                $this->elasticGoods->searchById($singleProduct['id'])
            );

            $this->elasticGoods->load(
                ArrayHelper::merge($currentData, $formattedData)
            )->index();

            $goodsIndexed[] = $singleProduct['id'];
        }, $productData);

        DB::table('goods')
            ->whereIn('id', $goodsIndexed)
            ->update(['needs_index' => 0]);
    }
}

<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\CommonFormatter;
use App\Helpers\QueryBuilderHelper;
use App\Models\GraphQL\GoodsBatchModel;
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
     * @var GoodsBatchModel
     */
    protected GoodsBatchModel $goodsBatch;

    /**
     * MigrateGoodsCommand constructor.
     * @param GoodsBatchModel $goodsBatch
     */
    public function __construct(GoodsBatchModel $goodsBatch)
    {
        $this->goodsBatch = $goodsBatch;

        parent::__construct();
    }

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

                $goods = $this->goodsBatch->getDefaultDataByGoodsIds($goodsIds);

                $dataArray = [
                    'goods' => [],
                    'goods_options' => [],
                    'goods_options_plural' => [],
                    'options' => [],
                    'option_values' => [],
                    'producers' => [],
                ];

                foreach ($goods as $productData) {
                    $dataArray['goods'][] = CommonFormatter::t_Goods($productData);
                    $producers = CommonFormatter::t_Producers($productData);
                    if (!empty($producers)) {
                        $dataArray['producers'][] = $producers;
                    }
                    $dataArray['options'] = array_merge($dataArray['options'], CommonFormatter::t_Options($productData));
                    $dataArray['option_values'] = array_merge($dataArray['option_values'], CommonFormatter::t_OptionValues($productData));
                    $dataArray['goods_options'] = array_merge($dataArray['goods_options'], CommonFormatter::t_GoodsOptions($productData));
                    $dataArray['goods_options_plural'] = array_merge($dataArray['goods_options_plural'], CommonFormatter::t_GoodsOptionsPlural($productData));
                }

                foreach ($dataArray as $table => $data) {
                    DB::table($table)->insertOrIgnore($data);
                }

                DB::table('promotion_goods_constructors')
                    ->whereIn('goods_id', $goodsIds)
                    ->update(['needs_index' => 0]);
            });

        }, true);
    }
}

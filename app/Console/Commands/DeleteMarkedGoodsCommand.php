<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\ExtCommand;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Facades\DB;

class DeleteMarkedGoodsCommand extends ExtCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:delete-marked-goods';

    /**
     * @var string
     */
    protected $description = 'Delete marked rows in DB';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elasticGoods;

    protected const GOODS_COUNT_LIMIT = 500;

    /**
     * DeleteMarkedGoodsCommand constructor.
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
    protected function extHandle()
    {
        $goodsQuery = DB::table('goods')
            ->select('id')
            ->where(['is_deleted' => 1])
            ->limit(self::GOODS_COUNT_LIMIT);

        do {
            $goods = $goodsQuery->get();
            $countGoods = $goods->count();

            if ($countGoods == 0) {
                break;
            }

            $deletedGoods = [];
            $goods->map(function ($product) use (&$deletedGoods) {
                $this->elasticGoods->delete(['id' => $product->id]);

                $deletedGoods[] = $product->id;
            });

            DB::table('goods')->whereIn('id', $deletedGoods)->delete();
        } while($countGoods == self::GOODS_COUNT_LIMIT);
    }
}

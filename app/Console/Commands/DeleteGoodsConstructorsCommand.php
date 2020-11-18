<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\ExtCommand;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Facades\DB;

class DeleteGoodsConstructorsCommand extends ExtCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:delete-goods-constructors';

    /**
     * @var string
     */
    protected $description = 'Delete goods_constructors from index and DB';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elasticGoods;

    /**
     * DeleteGoodsConstructorsCommand constructor.
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
        $deleted = DB::connection('nimble_read')
            ->table('promotion_goods_constructors')
            ->select(['constructor_id', 'goods_id'])
            ->where(['is_deleted' => 1])
            ->get();

        $params = ['body' => []];
        $deleted->map(function ($item) use (&$params) {
            $params['body'][] = [
                'update' => [
                    '_index' => $this->elasticGoods->indexName(),
                    '_id' => $item->goods_id,
                ],
            ];

            $params['body'][] = [
                'script' => [
                    'source' => 'ctx._source.promotion_constructors.removeIf(promotion_constructors -> promotion_constructors.id == params.constructor_id)',
                    'params' => [
                        'constructor_id' => $item->constructor_id
                    ],
                ]
            ];
        });

        if (!empty($params['body'])) {
            $this->elasticGoods->bulk($params);
        }

        DB::table('promotion_goods_constructors')
            ->where(['is_deleted' => 1])
            ->delete();
    }

}

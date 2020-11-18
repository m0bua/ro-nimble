<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\ExtCommand;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Facades\DB;

class DeleteGroupsConstructorsCommand extends ExtCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:delete-groups-constructors';

    /**
     * @var string
     */
    protected $description = 'Delete groups_constructors from index and DB';

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
            ->table('promotion_groups_constructors')
            ->select(['constructor_id', 'group_id'])
            ->where(['is_deleted' => 1])
            ->get();

        $params = ['body' => []];
        $deleted->map(function ($item) use (&$params) {

            $goods = DB::connection('nimble_read')
                ->table('goods')
                ->select(['id'])
                ->where(['group_id' => $item->group_id])
                ->get();

            $goods->map(function ($product) use (&$params, $item) {
                $params['body'][] = [
                    'update' => [
                        '_index' => $this->elasticGoods->indexName(),
                        '_id' => $product->id,
                    ],
                ];

                $params['body'][] = [
                    'script' => [
                        'source' => 'ctx._source.promotion_constructors.removeIf(promotion_constructors -> promotion_constructors.id == params.constructor_id)',
                        'params' => [
                            'constructor_id' => $item->constructor_id
                        ],
                    ],
                ];
            });
        });

        if (!empty($params['body'])) {
            $this->elasticGoods->bulk($params);
        }

        DB::table('promotion_groups_constructors')
            ->where(['is_deleted' => 1])
            ->delete();
    }

}

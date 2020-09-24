<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\QueryBuilderHelper;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Facades\DB;

class IndexGoodsGroupsConstructors extends CustomCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:index-goods-groups-constructors';

    /**
     * @var string
     */
    protected $description = 'Indexing promotion goods by groups';

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

            $constructorsQuery = DB::table('promotion_constructors as pc')
                ->select([
                    'pc.id',
                    'pc.promotion_id',
                    'pc.gift_id',
                    'pgc.group_id',
                ])
                ->join('promotion_groups_constructors as pgc', 'pc.id', '=', 'pgc.constructor_id');

            QueryBuilderHelper::chunk(500, $constructorsQuery, function ($constructors) {
                $constructorsData = [];
                $constructors->map(function ($constructor) use (&$constructorsData) {

                    $goods = DB::table('goods')
                        ->select('id')
                        ->where(['group_id' => $constructor->group_id])
                        ->get();

                    $goods->map(function ($product) use (&$constructorsData, $constructor) {
                        $constructorsData[$product->id][] = [
                            'id' => $constructor->id,
                            'promotion_id' => $constructor->promotion_id,
                            'gift_id' => $constructor->gift_id,
                        ];
                    });
                });

                $updateData = ['body' => []];
                foreach ($constructorsData as $goodsId => $constructors) {
                    $updateData['body'][] = [
                        'update' => [
                            '_index' => $this->elasticGoods->indexName(),
                            '_id' => $goodsId
                        ],
                    ];
                    $updateData['body'][] = [
                        'doc' => [
                            'promotion_constructors' => $constructors
                        ]
                    ];
                }

                $this->elasticGoods->bulk($updateData);
            });
        }, true);
    }
}

<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\QueryBuilderHelper;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Facades\DB;

class IndexGoodsConstructors extends CustomCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:index-goods-constructors';

    /**
     * @var string
     */
    protected $description = 'Indexing promotion goods constructors';

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
                    'pgc.goods_id',
                ])
                ->join('promotion_goods_constructors as pgc', 'pc.id', '=', 'pgc.constructor_id')
                ->where(['pc.needs_index' => 1]);

            QueryBuilderHelper::chunk(500, $constructorsQuery, function ($constructors) {
                $constructorIDs = [];
                $constructorsData = [];
                $constructors->map(function ($constructor) use (&$constructorsData, &$constructorIDs) {
                    $constructorIDs[] = $constructor->id;
                    $constructorsData[$constructor->goods_id][] = [
                        'id' => $constructor->id,
                        'promotion_id' => $constructor->promotion_id,
                        'gift_id' => $constructor->gift_id,
                    ];
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
                DB::table('promotion_constructors')
                    ->whereIn('id', $constructorIDs)
                    ->update(['needs_index' => 0]);
            });
        });
    }
}

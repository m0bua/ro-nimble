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
                    'g.id as goods_id',
                ])
                ->join('promotion_groups_constructors as pgc', 'pc.id', '=', 'pgc.constructor_id')
                ->join('goods as g', 'pgc.group_id', '=', 'g.group_id')
                ->where(['pc.needs_index' => 1]);

            $constructorIDs = [];

            QueryBuilderHelper::chunk($constructorsQuery, function ($constructors) use (&$constructorIDs) {
                $constructorsData = [];
                $errorConstructorsIDs = [];

                array_map(function ($constructor) use (&$constructorsData, &$constructorIDs) {
                    $constructorIDs[$constructor->id] = $constructor->id;
                    $constructorsData[$constructor->goods_id] = [
                        'id' => $constructor->id,
                        'promotion_id' => $constructor->promotion_id,
                        'gift_id' => $constructor->gift_id,
                    ];
                }, $constructors);

                $updateData = ['body' => []];
                foreach ($constructorsData as $goodsId => $constructor) {
                    $updateData['body'][] = [
                        'update' => [
                            '_index' => $this->elasticGoods->indexName(),
                            '_id' => $goodsId
                        ],
                    ];
                    $updateData['body'][] = [
                        'script' => [
                            'source' => <<< EOF
                                if (ctx._source.promotion_constructors != null) {
                                    ctx._source.promotion_constructors.removeIf(promotion_constructors -> promotion_constructors.id == params.constructor_id);
                                    ctx._source.promotion_constructors.add(params.constructor);
                                } else {
                                    ctx._source['promotion_constructors'] = [params.constructor];
                                }
                            EOF,
                            'params' => [
                                'constructor' => $constructor,
                                'constructor_id' => $constructor['id']
                            ],
                        ],
                    ];
                }

                $bulkResult = $this->elasticGoods->bulk($updateData);

                if ($bulkResult['errors']) {
                    foreach ($bulkResult['items'] as $item) {
                        if ($item['update']['status'] !== 200) {
                            $itemId = (int)$item['update']['_id'];
                            $errorConstructorsIDs[$itemId] = $itemId;
                        }
                    }
                }

                if ($errorConstructorsIDs) {
                    DB::table('goods')
                        ->whereIn('id', $errorConstructorsIDs)
                        ->update(['needs_index' => 1]);
                }
            });

            if ($constructorIDs) {
                foreach (array_chunk($constructorIDs, 500) as $ids) {
                    DB::table('promotion_constructors')
                        ->whereIn('id', $ids)
                        ->update(['needs_index' => 0]);
                }
            }
        });
    }
}

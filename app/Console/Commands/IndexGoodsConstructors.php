<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\ExtCommand;
use App\Helpers\Chunks\ChunkCursor;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Facades\DB;

class IndexGoodsConstructors extends ExtCommand
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
    protected function extHandle()
    {
        $constructorsGoodsQuery = DB::connection('nimble_read')
            ->table('promotion_goods_constructors as pgc')
            ->select([
                'pc.id',
                'pc.promotion_id',
                'pc.gift_id',
                'pgc.goods_id',
                'pgc.id as pgc_id',
            ])
            ->join('promotion_constructors as pc', 'pgc.constructor_id', '=', 'pc.id')
            ->where(['pgc.needs_index' => 1]);

        $promotionGoodsConstructorsIDs = [];

        ChunkCursor::iterate($constructorsGoodsQuery, function ($constructors) use (&$promotionGoodsConstructorsIDs) {
            $constructorsData = [];
            $errorConstructorsIDs = [];

            array_map(function ($constructor) use (&$constructorsData, &$promotionGoodsConstructorsIDs) {
                $promotionGoodsConstructorsIDs[$constructor->pgc_id] = $constructor->pgc_id;
                $constructorsData[$constructor->goods_id][] = [
                    'id' => $constructor->id,
                    'promotion_id' => $constructor->promotion_id,
                    'gift_id' => $constructor->gift_id,
                ];
            }, $constructors);

            $updateData = ['body' => []];
            foreach ($constructorsData as $goodsId => $constructors) {
                foreach ($constructors as $constructor) {
                    $updateData['body'][] = [
                        'update' => [
                            '_index' => $this->elasticGoods->indexName(),
                            '_id' => $goodsId
                        ],
                    ];
                    $updateData['body'][] = [
                        'script' => [
                            'lang' => 'painless',
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

        if ($promotionGoodsConstructorsIDs) {
            foreach (array_chunk($promotionGoodsConstructorsIDs, 500) as $ids) {
                DB::table('promotion_goods_constructors')
                    ->whereIn('id', $ids)
                    ->update(['needs_index' => 0]);
            }
        }
    }
}

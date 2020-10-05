<?php
namespace App\Console\Commands;

use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\QueryBuilderHelper;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Facades\DB;

class IndexGoodsProducersCommand extends CustomCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:index-goods-producers';

    /**
     * @var string
     */
    protected $description = 'Indexing goods producers';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elasticGoods;

    /**
     * IndexGoodsProducersCommand constructor.
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
            $producersQuery = DB::table('producers as p')
                ->select([
                    'g.id as goods_id',
                    'p.id',
                    'p.name',
                    'p.title',
                ])
                ->join('goods as g', 'p.id', '=', 'g.producer_id')
                ->where(['p.needs_index' => 1]);

            $producerIDs = [];

            QueryBuilderHelper::chunk($producersQuery, function ($producers) use (&$producerIDs) {
                $producersData = [];
                $errorGoodsIDs = [];

                array_map(function ($producer) use (&$producersData, &$producerIDs) {
                    $producerIDs[$producer->id] = $producer->id;
                    $producersData[$producer->goods_id] = [
                        'producer_id' => $producer->id,
                        'producer_title' => $producer->title,
                        'producer_name' => $producer->name,
                    ];
                }, $producers);

                $updateData = ['body' => []];

                foreach ($producersData as $goodsId => $producerData) {
                    $updateData['body'][] = [
                        'update' => [
                            '_index' => $this->elasticGoods->indexName(),
                            '_id' => $goodsId
                        ],
                    ];
                    $updateData['body'][] = [
                        'doc' => $producerData
                    ];
                }

                $bulkResult = $this->elasticGoods->bulk($updateData);

                if ($bulkResult['errors']) {
                    foreach ($bulkResult['items'] as $item) {
                        if ($item['update']['status'] !== 200) {
                            $itemId = (int)$item['update']['_id'];
                            $errorGoodsIDs[$itemId] = $itemId;
                        }
                    }
                }

                if ($errorGoodsIDs) {
                    DB::table('goods')
                        ->whereIn('id', $errorGoodsIDs)
                        ->update(['needs_index' => 1]);
                }
            });

            if ($producerIDs) {
                foreach (array_chunk($producerIDs, 500) as $ids) {
                    DB::table('producers')
                        ->whereIn('id', $ids)
                        ->update(['needs_index' => 0]);
                }
            }
        });
    }
}

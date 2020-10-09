<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\Chunks\ChunkCursor;
use App\Models\Elastic\GoodsModel;
use App\ValueObjects\Options;
use Illuminate\Support\Facades\DB;

class IndexGoodsOptionsCommand extends CustomCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:index-goods-options';

    /**
     * @var string
     */
    protected $description = 'Indexing goods options';

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
            $baseQuery = DB::table('goods_options as go')
                ->select([
                    'go.goods_id',
                    'go.option_id',
                    'go.value',
                    'o.type',
                    'o.name',
                    'o.state',
                ])
                ->join('options as o', 'go.option_id', '=', 'o.id')
                ->where([
                    ['go.needs_index', '=', 1],
                    ['o.state', '=', 'active'],
                ])
                ->whereIn('o.type', ['CheckBox', 'Integer', 'Decimal']);

            ChunkCursor::iterate($baseQuery, function ($goodsOptions) {
                $options = [];
                $optsForUpdate = [];
                foreach ($goodsOptions as $item) {
                    if (in_array($item->type, Options::OPTIONS_BY_TYPES['integers'])) {
                        $options[$item->goods_id]['option_sliders'][] = [
                            'id' => $item->option_id,
                            'name' => $item->name,
                            'value' => $item->value,
                        ];
                    } elseif (in_array($item->type, Options::OPTIONS_BY_TYPES['booleans'])) {
                        $options[$item->goods_id]['option_checked'][] = $item->option_id;
                        $options[$item->goods_id]['option_checked_names'][] = $item->name;
                    }

                    $optsForUpdate[$item->goods_id][] = $item->option_id;
                }

                $updateData = ['body' => []];
                foreach ($options as $goodsId => $optionsData) {
                    $updateData['body'][] = [
                        'update' => [
                            '_index' => $this->elasticGoods->indexName(),
                            '_id' => $goodsId,
                        ],
                    ];
                    $updateData['body'][] = [
                        'doc' => $optionsData
                    ];
                }

                $bulkResult = $this->elasticGoods->bulk($updateData);
                if ($bulkResult['errors']) {
                    foreach ($bulkResult['items'] as $item) {
                        if ($item['update']['status'] !== 200) {
                            $itemId = (int)$item['update']['_id'];
                            unset($options[$itemId]);
                        }
                    }
                }

                foreach ($optsForUpdate as $goodsId => $optIds) {
                    DB::table('goods_options')
                        ->where(['goods_id' => $goodsId])
                        ->whereIn('option_id', $optIds)
                        ->update(['needs_index' => 0]);
                }
            });
        },true);
    }
}

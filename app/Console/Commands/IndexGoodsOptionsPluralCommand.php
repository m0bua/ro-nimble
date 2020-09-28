<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\CustomCommand;
use App\Helpers\QueryBuilderHelper;
use App\Models\Elastic\GoodsModel;
use App\ValueObjects\Options;
use Illuminate\Support\Facades\DB;

class IndexGoodsOptionsPluralCommand extends CustomCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:index-goods-options-plural';

    /**
     * @var string
     */
    protected $description = 'Indexing goods options plural';

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
            $baseQuery = DB::table('goods_options_plural as gol')
                ->select([
                    'gol.goods_id',
                    'gol.option_id',
                    'gol.value_id',
                    'o.type',
                    'o.name as option_name',
                    'o.state',
                    'ov.status as value_status',
                    'ov.name as value_name',
                ])
                ->join('options as o', 'gol.option_id', '=', 'o.id')
                ->join('option_values as ov', 'gol.value_id', '=', 'ov.id')
                ->where([
                    ['gol.needs_index', '=', 1],
                    ['o.state', '=', 'active'],
                ])
                ->whereIn('o.type', Options::OPTIONS_BY_TYPES['values'])
                ->orderBy('gol.goods_id');

            QueryBuilderHelper::chunk(500, $baseQuery, function ($goodsOptions) {
                $options = [];
                $goodsOptions->map(function ($item) use (&$options) {
                    $options[$item->goods_id]['options'][] = $item->option_id;
                    $options[$item->goods_id]['option_names'][] = $item->option_name;
                    if ($item->value_status == 'active') {
                        $options[$item->goods_id]['option_values'][] = $item->value_id;
                        $options[$item->goods_id]['option_values_name'][] = $item->value_name;
                    }
                });

                /**
                 * Clear duplicates
                 */
                foreach ($options as &$tmp_opt) {
                    $tmp_opt['options'] = array_values(array_unique($tmp_opt['options'], SORT_REGULAR));
                    $tmp_opt['option_names'] = array_values(array_unique($tmp_opt['option_names'], SORT_REGULAR));
                    if (isset($tmp_opt['option_values'])) {
                        $tmp_opt['option_values'] = array_values(array_unique($tmp_opt['option_values'], SORT_REGULAR));
                        $tmp_opt['option_values_name'] = array_values(array_unique($tmp_opt['option_values_name'], SORT_REGULAR));
                    }
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

                foreach ($options as $goodsId => $optionData) {
                    $table = DB::table('goods_options_plural')
                        ->where(['goods_id' => $goodsId])
                        ->whereIn('option_id', $optionData['options']);

                    if (isset($optionData['option_values'])) {
                        $table->whereIn('value_id', $optionData['option_values']);
                    }

                    $table->update(['needs_index' => 0]);
                }
            });
        });
    }
}

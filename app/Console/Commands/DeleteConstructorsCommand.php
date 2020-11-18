<?php


namespace App\Console\Commands;


use App\Console\Commands\Extend\ExtCommand;
use App\Models\Elastic\GoodsModel;
use Illuminate\Support\Facades\DB;

class DeleteConstructorsCommand extends ExtCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:delete-constructors';

    /**
     * @var string
     */
    protected $description = 'Delete constructors from index and DB';

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
            ->table('promotion_constructors')
            ->select(['id'])
            ->where(['is_deleted' => 1])
            ->get();

        $params = ['body' => []];
        $deleted->map(function ($constructor) use (&$params) {

            /**
             * Get all search results, using scrolling
             * https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/search_operations.html#_scrolling
             */
            $records = $this->elasticGoods->search([
                'scroll' => '30s',
                'size' => 50,
                '_source' => ['id'],
                'body' => [
                    'query' => [
                        'term' => [
                            'promotion_constructors.id' => $constructor->id,
                        ]
                    ],
                ],
            ]);

            while (isset($records['hits']['hits']) && count($records['hits']['hits']) > 0) {
                foreach (array_column($records['hits']['hits'], '_source') as $source) {
                    $params['body'][] = [
                        'update' => [
                            '_index' => $this->elasticGoods->indexName(),
                            '_id' => $source['id'],
                        ],
                    ];

                    $params['body'][] = [
                        'script' => [
                            'source' => 'ctx._source.promotion_constructors.removeIf(promotion_constructors -> promotion_constructors.id == params.constructor_id)',
                            'params' => [
                                'constructor_id' => $constructor->id
                            ],
                        ]
                    ];
                }

                $scrollId = $records['_scroll_id'];
                $records = $this->elasticGoods->scroll([
                    'body' => [
                        'scroll_id' => $scrollId,
                        'scroll' => '30s',
                    ],
                ]);
            }
        });

        if (!empty($params['body'])) {
            $this->elasticGoods->bulk($params);
        }

        DB::table('promotion_constructors')
            ->where(['is_deleted' => 1])
            ->delete();
    }

}

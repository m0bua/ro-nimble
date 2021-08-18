<?php

namespace App\Console\Commands\Delete;

use App\Console\Commands\Command;
use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\PromotionConstructor;

class DeleteConstructors extends Command
{
    /**
     * @var string
     */
    protected $signature = 'db:delete-constructors';

    /**
     * @var string
     */
    protected $description = 'Delete constructors form Elasticsearch index and Database';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elasticGoods;

    /**
     * @var PromotionConstructor
     */
    protected PromotionConstructor $model;

    protected array $params = [
        'body' => [],
    ];

    /**
     * DeleteConstructors constructor.
     * @param GoodsModel $elasticGoods
     * @param PromotionConstructor $model
     */
    public function __construct(GoodsModel $elasticGoods, PromotionConstructor $model)
    {
        $this->elasticGoods = $elasticGoods;
        $this->model = $model;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function proceed(): void
    {
        $deletedConstructorIds = $this->model
            ->markedAsDeleted()
            ->pluck('id')
            ->each(function (int $id) {
                $records = $this->scrollRecordsByConstructorId($id);

                while ($hits = $this->resolveHitsFromRecords($records)) {
                    $sources = $this->pluckSourcesFromHits($hits);

                    foreach ($sources as $source) {
                        $this->params['body'][] = $this->buildUpdateInstruction($source['id']);
                        $this->params['body'][] = $this->buildScriptInstruction($id);
                    }

                    $records = $this->scrollRecordsByScrollId($records['_scroll_id']);
                }
            });

        if ($this->params['body']) {
            $this->elasticGoods->bulk($this->params);
        }

        if ($deletedConstructorIds->isNotEmpty()) {
            $this->model
                ->whereIn('id', $deletedConstructorIds)
                ->delete();
        }
    }

    /**
     * Get all search results via scrolling
     * @link https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/search_operations.html#_scrolling
     *
     * @param int $id
     * @return array|callable
     */
    private function scrollRecordsByConstructorId(int $id)
    {
        return $this->elasticGoods->search([
            'scroll' => '30s',
            'size' => 50,
            '_source' => [
                'id'
            ],
            'body' => [
                'query' => [
                    'term' => [
                        'promotion_constructors.id' => $id,
                    ]
                ],
            ],
        ]);
    }

    /**
     * Scroll records by provided _scroll_id
     *
     * @param string $scrollId
     * @return array|callable
     */
    private function scrollRecordsByScrollId(string $scrollId)
    {
        return $this->elasticGoods->scroll([
            'body' => [
                'scroll_id' => $scrollId,
                'scroll' => '30s',
            ],
        ]);
    }

    /**
     * Resolve hits from fetched records
     *
     * @param array $records
     * @return array
     */
    private function resolveHitsFromRecords(array $records): array
    {
        return $records['hits']['hits'] ?? [];
    }

    /**
     * Pluck _source column from array of hits
     *
     * @param array $hits
     * @return array
     */
    private function pluckSourcesFromHits(array $hits): array
    {
        return array_column($hits, '_source');
    }

    /**
     * Build update instruction with provided id
     *
     * @param int $id
     * @return array
     */
    private function buildUpdateInstruction(int $id): array
    {
        return [
            'update' => [
                '_index' => $this->elasticGoods->getIndexName(),
                '_id' => $id,
            ],
        ];
    }

    /**
     * Build script instruction with provided id
     *
     * @param int $id
     * @return array
     */
    private function buildScriptInstruction(int $id): array
    {
        return [
            'script' => [
                'source' => "ctx._source.promotion_constructors.removeIf(promotion_constructors -> promotion_constructors.id == params.constructor_id)",
                'params' => [
                    'constructor_id' => $id,
                ],
            ]
        ];
    }
}

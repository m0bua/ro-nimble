<?php

namespace App\Console\Commands\Delete;

use App\Console\Commands\Command;
use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\PromotionGoodsConstructor;

class DeleteGoodsConstructors extends Command
{
    /**
     * @var string
     */
    protected $signature = 'db:delete-goods-constructors';

    /**
     * @var string
     */
    protected $description = 'Delete goods constructors form Elasticsearch index and Database';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elasticGoods;

    /**
     * @var PromotionGoodsConstructor
     */
    protected PromotionGoodsConstructor $model;

    /**
     * @var array|array<array>
     */
    protected array $params = [
        'body' => [],
    ];

    /**
     * DeleteGoodsConstructors constructor.
     * @param GoodsModel $elasticGoods
     * @param PromotionGoodsConstructor $model
     */
    public function __construct(GoodsModel $elasticGoods, PromotionGoodsConstructor $model)
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
        $this->model
            ->markedAsDeleted()
            ->get([
                'id',
                'constructor_id',
                'goods_id',
            ])
            ->each(function (PromotionGoodsConstructor $constructor) {
                $this->params['body'][] = $this->buildUpdateInstruction($constructor->goods_id);
                $this->params['body'][] = $this->buildScriptInstruction($constructor->constructor_id);
            });

        if ($this->params['body']) {
            $this->elasticGoods->bulk($this->params);
        }

        $this->model->markedAsDeleted()->delete();
    }

    /**
     * Build update instruction with provided id
     *
     * @param int $id
     * @return array<array>
     */
    private function buildUpdateInstruction(int $id): array
    {
        return [
            'update' => [
                '_index' => $this->elasticGoods->indexName(),
                '_id' => $id,
            ],
        ];
    }

    /**
     * Build script instruction with provided id
     *
     * @param int $id
     * @return array<array>
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

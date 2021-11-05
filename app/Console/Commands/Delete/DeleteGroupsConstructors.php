<?php

namespace App\Console\Commands\Delete;

use App\Console\Commands\Command;
use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\PromotionGroupConstructor;

class DeleteGroupsConstructors extends Command
{
    /**
     * @var string
     */
    protected $signature = 'db:delete-groups-constructors';

    /**
     * @var string
     */
    protected $description = 'Delete groups constructors form Elasticsearch index and Database';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elasticGoods;

    /**
     * @var PromotionGroupConstructor
     */
    protected PromotionGroupConstructor $model;

    /**
     * @var Goods
     */
    protected Goods $goods;

    protected array $params = [
        'body' => [],
    ];

    /**
     * DeleteGroupsConstructors constructor.
     * @param GoodsModel $elasticGoods
     * @param PromotionGroupConstructor $model
     * @param Goods $goods
     */
    public function __construct(GoodsModel $elasticGoods, PromotionGroupConstructor $model, Goods $goods)
    {
        $this->elasticGoods = $elasticGoods;
        $this->model = $model;
        $this->goods = $goods;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function proceed(): void
    {
        $deletedConstructors = $this->model
            ->markedAsDeleted()
            ->get([
                'id',
                'constructor_id',
                'group_id',
            ])
            ->each(function (PromotionGroupConstructor $constructor) {
                $this->goods
                    ->whereGroupId($constructor->group_id)
                    ->pluck('id')
                    ->each(function (int $id) use ($constructor) {
                        $this->params['body'][] = $this->buildUpdateInstructions($id);
                        $this->params['body'][] = $this->buildScriptInstructions($constructor->constructor_id);
                    });
            });

        if ($this->params['body']) {
            $this->elasticGoods->bulk($this->params);
        }

        if ($deletedConstructors->isNotEmpty()) {
            $this->model
                ->whereIn('id', $deletedConstructors->pluck('id'))
                ->delete();
        }
    }

    /**
     * @param int $goodsId
     * @return array<array>
     */
    private function buildUpdateInstructions(int $goodsId): array
    {
        return [
            'update' => [
                '_index' => $this->elasticGoods->indexName(),
                '_id' => $goodsId,
            ],
        ];
    }

    /**
     * @param int $id
     * @return array<array>
     */
    private function buildScriptInstructions(int $id): array
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

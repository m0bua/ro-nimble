<?php

namespace App\Console\Commands\Index;

use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\PromotionGoodsConstructor;
use Illuminate\Database\Eloquent\Builder;

class IndexGoodsConstructors extends IndexCommand
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
    protected GoodsModel $elastic;

    /**
     * @var PromotionGoodsConstructor
     */
    protected PromotionGoodsConstructor $model;

    /**
     * IndexMarkedGoodsCommand constructor.
     * @param GoodsModel $elastic
     * @param PromotionGoodsConstructor $model
     */
    public function __construct(GoodsModel $elastic, PromotionGoodsConstructor $model)
    {
        $this->elastic = $elastic;
        $this->model = $model;

        parent::__construct();
    }

    /**
     * Build main query
     *
     * @return Builder
     */
    protected function buildQuery(): Builder
    {
        return $this->model
            ->where('needs_index', 1)
            ->select([
                'id',
                'goods_id',
                'constructor_id',
            ]);
    }

    /**
     * @inheritDoc
     * @param PromotionGoodsConstructor $entity
     */
    protected function operateWithEntity($entity): void
    {
        $entity->load([
            'constructor:id,promotion_id,gift_id',
        ]);

        if (!$entity->constructor->exists) {
            return;
        }

        $this->allIds[] = $entity->id;

        $this->data[$entity->goods_id] = [
            'id' => $entity->constructor->id,
            'promotion_id' => $entity->constructor->promotion_id,
            'gift_id' => $entity->constructor->gift_id,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function buildUpdateOperation(int $id): array
    {
        return [
            'update' => [
                '_index' => $this->elastic->indexName(),
                '_id' => $id
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function buildScriptOperation(array $entity): array
    {
        return [
            'script' => [
                'lang' => 'painless',
                'source' => <<< SCRIPT
                                if (ctx._source.promotion_constructors != null) {
                                    ctx._source.promotion_constructors.removeIf(promotion_constructors -> promotion_constructors.id == params.constructor_id);
                                    ctx._source.promotion_constructors.add(params.constructor);
                                } else {
                                    ctx._source['promotion_constructors'] = [params.constructor];
                                }
                             SCRIPT,
                'params' => [
                    'constructor' => $entity,
                    'constructor_id' => $entity['id']
                ],
            ],
        ];
    }
}

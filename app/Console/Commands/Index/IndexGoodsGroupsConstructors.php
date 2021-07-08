<?php

namespace App\Console\Commands\Index;

use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\PromotionGroupConstructor;
use Illuminate\Database\Eloquent\Builder;

class IndexGoodsGroupsConstructors extends IndexCommand
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
    protected GoodsModel $elastic;

    /**
     * @var PromotionGroupConstructor
     */
    protected PromotionGroupConstructor $model;

    /**
     * IndexMarkedGoodsCommand constructor.
     * @param GoodsModel $elastic
     * @param PromotionGroupConstructor $model
     */
    public function __construct(GoodsModel $elastic, PromotionGroupConstructor $model)
    {
        $this->elastic = $elastic;
        $this->model = $model;

        parent::__construct();
    }

    /**
     * Build base query
     *
     * @return Builder
     */
    protected function buildQuery(): Builder
    {
        return $this->model
            ->needsIndex()
            ->select([
                'id',
                'group_id',
                'constructor_id',
            ]);
    }

    /**
     * @inheritDoc
     * @param PromotionGroupConstructor $entity
     */
    protected function operateWithEntity($entity): void
    {
        $entity->load([
            'constructor:id,promotion_id,gift_id',
            'goods:id,group_id',
        ]);

        if (!$entity->constructor->exists) {
            return;
        }

        $this->allIds[] = $entity->id;

        foreach ($entity->goods as $goods) {
            $this->data[$goods->id] = [
                'id' => $entity->constructor->id,
                'promotion_id' => $entity->constructor->promotion_id,
                'gift_id' => $entity->constructor->gift_id,
            ];
        }
    }

    /**
     * Build update operation
     *
     * @param int $id
     * @return array
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
     * Build script operation
     *
     * @param array $entity
     * @return array
     */
    protected function buildScriptOperation(array $entity): array
    {
        return [
            'script' => [
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

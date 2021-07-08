<?php

namespace App\Console\Commands\Index;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\Goods;
use App\Support\Language;
use Illuminate\Database\Eloquent\Builder;

class IndexMarkedGoods extends IndexCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:index-marked-goods';

    /**
     * @var string
     */
    protected $description = 'Fill index goods table';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elastic;

    /**
     * @var Goods
     */
    protected Goods $model;

    /**
     * IndexMarkedGoodsCommand constructor.
     * @param GoodsModel $elastic
     * @param Goods $model
     */
    public function __construct(GoodsModel $elastic, Goods $model)
    {
        $this->elastic = $elastic;
        $this->model = $model;

        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function buildQuery(): Builder
    {
        return $this->model->needsIndex();
    }

    /**
     * @inheritDoc
     * @param Goods $entity
     */
    protected function operateWithEntity($entity): void
    {
        $entity->load([
            'producer:id,name',
            'goodsOptions' => fn($q) => $q->where('type', '!=', 'unknown'),
            'goodsOptions.option',
            'options',
            'optionValues',
            'promotionGoodsConstructors' => fn($q) => $q->where('is_deleted', 0),
            'promotionGoodsConstructors.constructor',
        ]);
        if (isset($entity->group_id)) {
            $entity->load([
                'promotionGroupConstructors',
                'promotionGroupConstructors.constructor' => fn($q) => $q->where('is_deleted', 0),
            ]);
        }
        $datum = $entity->only([
            'id',
            'category_id',
            'mpath',
            'price',
            'sell_status',
            'producer_id',
            'seller_id',
            'group_id',
            'is_group_primary',
            'status_inherited',
            'order',
            'state',
            'rank',
            'producer_id',
        ]);
        $datum['producer_name'] = $entity->producer->name;
        $datum['producer_title'] = $entity->producer->getTranslation('title', Language::RU);
        $datum['promotion_constructors'] = [];
        $datum['options'] = [];

        foreach ($entity->goodsOptions as $goodsOption) {
            $option = $goodsOption->option;
            if (!$option->exists) {
                continue;
            }

            $datum['options'][$option->id]['details'] = [
                'id' => $option->id,
                'name' => $option->name,
                'type' => $option->type,
                'state' => $option->state,
            ];
            $datum['options'][$option->id]['value'] = $goodsOption->value;
        }

        foreach ($entity->optionValues as $optionValue) {
            $option = $optionValue->option;
            if (!$option->exists) {
                continue;
            }

            $datum['options'][$option->id]['details'] = [
                'id' => $option->id,
                'name' => $option->name,
                'type' => $option->type,
                'state' => $option->state,
            ];
            $datum['options'][$option->id]['values'][] = [
                'id' => $optionValue->id,
                'name' => $optionValue->name,
                'status' => $optionValue->status,
            ];
        }

        foreach ($entity->promotionGoodsConstructors as $promotionGoodsConstructor) {
            $constructor = $promotionGoodsConstructor->constructor;
            if (!$constructor->exists) {
                continue;
            }

            $datum['promotion_constructors'][] = [
                'id' => $constructor->id,
                'gift_id' => $constructor->gift_id,
                'promotion_id' => $constructor->promotion_id,
            ];
        }

        $groupsData = [];
        if ($entity->promotionGroupConstructors->isNotEmpty()) {
            foreach ($entity->promotionGroupConstructors as $promotionGroupConstructor) {
                $constructor = $promotionGroupConstructor->constructor;
                if (!$constructor->exists) {
                    continue;
                }

                $groupsData[$promotionGroupConstructor->group_id][] = [
                    'id' => $constructor->id,
                    'gift_id' => $constructor->gift_id,
                    'promotion_id' => $constructor->promotion_id,
                ];
            }
        }
        $formatter = (new CommonFormatter($datum))
            ->formatGoodsForIndex()
            ->formatOptionsForIndex()
            ->formatGroupsForIndex($groupsData);
        $this->allIds[] = $entity->id;
        $this->data[$entity->id] = $formatter->getFormattedData();
    }

    /**
     * @inheritDoc
     */
    protected function buildUpdateOperation(int $id): array
    {
        return [
            'update' => [
                '_index' => $this->elastic->indexName(),
                '_id' => $id,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function buildScriptOperation(array $entity): array
    {
        return [
            'doc' => $entity,
            'doc_as_upsert' => true,
        ];
    }
}

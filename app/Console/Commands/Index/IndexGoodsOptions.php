<?php

namespace App\Console\Commands\Index;

use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\GoodsOption;
use App\ValueObjects\Options;
use Illuminate\Database\Eloquent\Builder;

class IndexGoodsOptions extends IndexCommand
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
    protected GoodsModel $elastic;

    /**
     * @var GoodsOption
     */
    protected GoodsOption $model;

    /**
     * IndexGoodsOptionsCommand constructor.
     * @param GoodsModel $elastic
     * @param GoodsOption $model
     */
    public function __construct(GoodsModel $elastic, GoodsOption $model)
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
        return $this->model
            ->needsIndex()
            ->whereHas('option', function ($query) {
                $query->whereIn('type', ['CheckBox', 'Integer', 'Decimal'])
                    ->where('state', Options::STATUS_ACTIVE);
            })
            ->select([
                'id',
                'goods_id',
                'option_id',
                'value',
            ]);
    }

    /**
     * @inheritDoc
     * @param GoodsOption $entity
     */
    protected function operateWithEntity($entity): void
    {
        $entity->load([
            'option' => fn($q) => $q
                ->select([
                    'id',
                    'type',
                    'name',
                ])
        ]);

        if (!$entity->option->exists) {
            return;
        }

        if (in_array($entity->option->type, Options::OPTIONS_BY_TYPES['integers'], true)) {
            $this->data[$entity->goods_id]['option_sliders'][] = [
                'id' => $entity->option_id,
                'name' => $entity->option->name,
                'value' => $entity->value,
            ];
        } elseif (in_array($entity->option->type, Options::OPTIONS_BY_TYPES['booleans'], true)) {
            $this->data[$entity->goods_id]['option_checked'][] = $entity->option_id;
            $this->data[$entity->goods_id]['option_checked_names'][] = $entity->option->name;
        }

        $this->allIds[$entity->goods_id][] = $entity->option_id;
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
        ];
    }

    /**
     * @inheritDoc
     */
    protected function markEntitiesAsIndexed(): void
    {
        foreach ($this->allIds as $goodsId => $optionIds) {
            $this->model
                ->whereGoodsId($goodsId)
                ->whereIn('option_id', $optionIds)
                ->update(['needs_index' => 0]);
        }
    }
}

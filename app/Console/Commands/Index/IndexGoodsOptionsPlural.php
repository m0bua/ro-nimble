<?php

namespace App\Console\Commands\Index;

use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\GoodsOptionPlural;
use App\ValueObjects\Options;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class IndexGoodsOptionsPlural extends IndexCommand
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
    protected GoodsModel $elastic;

    /**
     * @var GoodsOptionPlural
     */
    protected GoodsOptionPlural $model;

    /**
     * IndexGoodsOptionsPluralCommand constructor.
     * @param GoodsModel $elastic
     * @param GoodsOptionPlural $model
     */
    public function __construct(GoodsModel $elastic, GoodsOptionPlural $model)
    {
        $this->elastic = $elastic;
        $this->model = $model;

        parent::__construct();
    }

    /**
     * @inheritDoc
     * @noinspection AlterInForeachInspection
     */
    protected function proceed(): void
    {
        DB::transaction(function () {
            $query = $this->buildQuery();
            $this->iterateQueryByCursor($query, [$this, 'operateWithEntity']);

            // Clear duplicates
            foreach ($this->data as &$option) {
                $option['options'] = array_values(array_unique($option['options'], SORT_REGULAR));
                $option['option_names'] = array_values(array_unique($option['option_names'], SORT_REGULAR));
                if (isset($option['option_values'])) {
                    $option['option_values'] = array_values(array_unique($option['option_values'], SORT_REGULAR));
                    $option['option_values_name'] = array_values(array_unique($option['option_values_name'], SORT_REGULAR));
                }
            }

            $this->buildElasticOperations();
            $result = $this->executeElasticOperations();

            $this->processElasticResult($result);

            $this->markEntitiesAsIndexed();
        });
    }

    /**
     * @inheritDoc
     */
    protected function markEntitiesAsIndexed(): void
    {
        foreach ($this->data as $goodsId => $optionData) {
            $query = $this->model
                ->where('goods_id', $goodsId)
                ->whereIn('option_id', $optionData['options']);

            if (isset($optionData['option_values'])) {
                $query->whereIn('value_id', $optionData['option_values']);
            }

            $query->update(['needs_index' => 0]);
        }
    }

    /**
     * @inheritDoc
     */
    protected function buildQuery(): Builder
    {
        return $this->model
            ->needsIndex()
            ->whereHas('option', function ($query) {
                $query->where('state', Options::STATUS_ACTIVE)
                    ->whereIn('type', Options::OPTIONS_BY_TYPES['values']);
            })
            ->select([
                'goods_id',
                'option_id',
                'value_id',
            ]);
    }

    /**
     * @inheritDoc
     * @param GoodsOptionPlural $entity
     */
    protected function operateWithEntity($entity): void
    {
        $entity->load([
            'option' => fn($q) => $q->select([
                'id',
                'type',
                'name',
                'state',
            ]),
            'value' => fn($q) => $q->select([
                'id',
                'status',
                'name',
            ])
        ]);

        if (!$entity->option->exists || !$entity->value->exists) {
            return;
        }

        $this->data[$entity->goods_id]['options'][] = $entity->option_id;
        $this->data[$entity->goods_id]['option_names'][] = $entity->option->name;

        if ($entity->value->status === Options::STATUS_ACTIVE) {
            $this->data[$entity->goods_id]['option_values'][] = $entity->value_id;
            $this->data[$entity->goods_id]['option_values_name'][] = $entity->value->name;
        }
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
}

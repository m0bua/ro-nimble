<?php

namespace App\Console\Commands\Index;

use App\Models\Elastic\GoodsModel;
use App\Models\Eloquent\Producer;
use App\Support\Language;
use Illuminate\Database\Eloquent\Builder;

class IndexGoodsProducers extends IndexCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:index-goods-producers';

    /**
     * @var string
     */
    protected $description = 'Indexing goods producers';

    /**
     * @var GoodsModel
     */
    protected GoodsModel $elastic;

    /**
     * @var Producer
     */
    protected Producer $model;

    /**
     * IndexGoodsProducersCommand constructor.
     * @param GoodsModel $elastic
     * @param Producer $model
     */
    public function __construct(GoodsModel $elastic, Producer $model)
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
            ->select(['id', 'name']);
    }

    /**
     * @inheritDoc
     * @param Producer $entity
     */
    protected function operateWithEntity($entity): void
    {
        $entity->load('goods:id,producer_id');

        $this->allIds[] = $entity->id;

        foreach ($entity->goods as $goods) {
            $this->data[$goods->id] = [
                'producer_id' => $entity->id,
                'producer_title' => $entity->getTranslation('title', Language::RU),
                'producer_name' => $entity->name,
            ];
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
                '_id' => $id
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function buildScriptOperation(array $entity): array
    {
        return [
            'doc' => $entity
        ];
    }
}

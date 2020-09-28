<?php

namespace App\Models\GraphQL;

use App\Traits\GSDefaultSelectionTrait;

class GoodsBatchModel extends GraphQL
{
    use GSDefaultSelectionTrait;

    /**
     * @inheritDoc
     */
    public function serviceName(): string
    {
        return 'goods';
    }

    /**
     * @inheritDoc
     */
    public function entityName(): string
    {
        return 'goodsBatch';
    }

    /**
     * @param array $goodsIds
     * @return array
     */
    public function getByIds(array $goodsIds): array
    {
        $this->query->setArguments(
            $this->whereIn('id', $goodsIds)
        );

        return $this->get();
    }

    /**
     * @param array $goodsIds
     * @return array
     */
    public function getDefaultDataByGoodsIds(array $goodsIds): array
    {
        $this->query->setSelectionSet([
            $this->query('nodes')->setSelectionSet($this->defaultSelectionSet())
        ]);

        return $this->getByIds($goodsIds)['nodes'];
    }

    /**
     * @param array $goodsIds
     * @param \Closure $callback
     * @param int $batchSize
     */
    public function getByBatch(array $goodsIds, \Closure $callback, int $batchSize = 100)
    {
        $batchId = 0;
        do {
            $this->query->setSelectionSet([
                $this->query('nodes')->setSelectionSet($this->defaultSelectionSet()),
                $this->query('batchInfo')->setSelectionSet(['batchSize', 'lastID']),
            ]);

            $this->query->setArguments(array_merge(
                $this->whereIn('id', $goodsIds),
                $this->batch($batchSize, $batchId)
            ));

            $result = $this->get();

            if ($result['batchInfo']['batchSize'] == 0) {
                break;
            }

            $callback($result['nodes']);

            $batchId = $result['batchInfo']['lastID'];
        } while($result['batchInfo']['batchSize'] == $batchSize);
    }
}

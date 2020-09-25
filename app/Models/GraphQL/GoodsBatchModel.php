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
        return $this->setArgumentsWhere('id_in', $goodsIds)->get();
    }

    /**
     * @param array $goodsIds
     * @return array
     */
    public function getDefaultDataByGoodsIds(array $goodsIds): array
    {
        return $this->setSelectionSet([
            $this->query('nodes')->setSelectionSet($this->defaultSelectionSet())
        ])->getByIds($goodsIds)['nodes'];
    }
}

<?php

namespace App\Models\GraphQL;

use App\Traits\GSDefaultSelectionTrait;

class GoodsManyModel extends GraphQL
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
        return 'goodsMany';
    }

    /**
     * @param int $groupId
     * @return array
     */
    public function getByGroupId(int $groupId): array
    {
        return $this->setArgumentsWhere('group_id_eq', $groupId)->get();
    }

    /**
     * @param int $groupId
     * @return array
     */
    public function getDefaultDataByGroupId(int $groupId): array
    {
        return $this->setSelectionSet([
            $this->query('nodes')->setSelectionSet($this->defaultSelectionSet())
        ])->getByGroupId($groupId)['nodes'];
    }
}

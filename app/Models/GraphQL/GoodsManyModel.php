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
        $this->query->setArguments(
            $this->where('group_id_eq', $groupId)
        );

        return $this->get();
    }

    /**
     * @param int $groupId
     * @return array
     */
    public function getDefaultDataByGroupId(int $groupId): array
    {
        $this->query->setSelectionSet([
            $this->query('nodes')->setSelectionSet($this->defaultSelectionSet())
        ]);

        return $this->getByGroupId($groupId)['nodes'];
    }
}

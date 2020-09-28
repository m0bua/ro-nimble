<?php

namespace App\Models\GraphQL;

use App\Traits\GSDefaultSelectionTrait;

class GoodsOneModel extends GraphQL
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
        return 'goodsOne';
    }

    /**
     * @param int $id
     * @return array
     */
    public function getDefaultDataById(int $id): array
    {
        $this->query->setSelectionSet($this->defaultSelectionSet());

        return $this->getById($id);
    }
}

<?php

namespace App\Models\Elastic\Promotions;

use App\Library\Services\Elastic;

class GoodsModel extends Elastic
{

    use PromotionsTrait;

    /**
     * @inheritDoc
     */
    public function typeName(): string
    {
        return 'goods';
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function save(array $params = [])
    {
        return $this->index(
            [
                'id' => $params['id'],
                'body' => $params
            ]
        );
    }

    /**
     * Ищет товар по ID
     *
     * @param int $goodsId
     * @return array|callable
     */
    public function searchById(int $goodsId)
    {
        return $this->search(
            [
                'body' => [
                    'query' => [
                        'multi_match' => [
                            'query' => $goodsId,
                            'fields' => ['id'],
                        ],
                    ],
                ],
            ]
        );
    }
}

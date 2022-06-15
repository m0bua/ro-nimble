<?php

namespace App\Models\Elastic;

/**
 * Class GoodsModel
 * @package App\Models\Elastic\Promotions
 *
 * @property integer $is_group_primary
 */
class GoodsModel extends Elastic
{
    /**
     * @return string
     */
    public function indexPrefix(): string
    {
        return 'goods';
    }

    /**
     * Ищет товар по ID
     *
     * @param int $goodsId
     * @return array|callable
     */
    public function searchById(int $goodsId)
    {
        return $this->searchTermByField('_id', $goodsId);
    }

    /**
     * @param string $fieldName
     * @param mixed $value
     * @return array
     */
    public function searchTermByField(string $fieldName, $value): array
    {
        $searchResult = $this->search(
            [
                'body' => [
                    'query' => [
                        'term' => [$fieldName => $value],
                    ],
                ],
            ]
        );

        return $this->getSource($searchResult);
    }
}

<?php

namespace App\Models\Elastic\Promotions;

use App\Models\Elastic\Elastic;
use App\ValueObjects\Property;

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
     * @return array
     */
    public function getFields(): array
    {
        return get_object_vars($this);
    }

    public $id;
    public $promotionId;
    public $constructorId;
    public $giftId;
    public $categoryId;
    public $categoryIds;
    public $options;
    public $optionNames;
    public $optionValues;
    public $optionValueNames;
    public $optionsChecked;
    public $optionsSliders;
    public $producerId;
    public $producerName;
    public $price;
    public $rank;
    public $sellStatus;
    public $statusInherited;
    public $sellerOrder;
    public $sellerId;
    public $incomeOrder;
    public $groupId;
    public $isGroupPrimary;
    public $goodsOrder;
    public $tags;
    public $bonusCharge;
    public $seriesId;
    public $state;

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

<?php

namespace App\Models\Elastic\Promotions;

use App\Library\Services\Elastic;
use App\Models\ModelTrait;
use App\ValueObjects\Property;
use ReflectionClass;
use ReflectionProperty;

class GoodsModel extends Elastic
{
    use ModelTrait;
    use PromotionsTrait;

    /**
     * @inheritDoc
     */
    public function typeName(): string
    {
        return 'goods';
    }

    private $id;
    private $promotionId;
    private $constructorId;
    private $giftId;
    private $categoryId;
    private $categoryIds;
    private $options;
    private $optionNames;
    private $optionValues;
    private $optionValueNames;
    private $optionsChecked;
    private $optionsSliders;
    private $producerId;
    private $producerName;
    private $price;
    private $rank;
    private $sellStatus;
    private $status_inherited;
    private $sellerOrder;
    private $sellerId;
    private $incomeOrder;
    private $groupId;
    private $isGroupPrimary;
    private $goodsOrder;
    private $tags;
    private $bonusCharge;
    private $seriesId;
    private $state;

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

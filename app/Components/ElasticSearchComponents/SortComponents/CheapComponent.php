<?php
/**
 * Класс для генерации сортировки по "От дешевым к дорогим"
 * Class CheapComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

use Illuminate\Support\Collection;

class CheapComponent extends BaseSortComponent
{
    /**
     * @return array[]
     */
    protected function getScript(): array
    {
        return [
            '_script' => [
                'type' => 'number',
                'script' => [
                    'lang' => 'painless',
                    'source' => <<< EOF
                        if (doc['price'].value == 0) {
                            return 99999;
                        }

                        int sell_status = 1;

                        if (doc['sell_status'].value == 'waiting_for_supply'
                            || doc['sell_status'].value == 'out_of_stock'
                            || doc['sell_status'].value == 'unavailable'
                        ) {
                            sell_status = 2;
                        }

                        return sell_status;
                    EOF
                ],
                'order' => 'asc'
            ]
        ];
    }

    /**
     * @inerhitDoc
     * @param \stdClass $product
     * @return int
     */
    protected static function getScriptOrder(\stdClass $product): int
    {
        if (0 == $product->price) {
            return 99999;
        }
        if (\in_array($product->sell_status, ['waiting_for_supply', 'out_of_stock', 'unavailable'])) {
            return 2;
        }

        return 1;
    }

    /**
     * @return array[]
     */
    private function getPrice(): array
    {
        return [
            'price' => [
                'order' => 'asc'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return [
            array_merge(
                $this->getScript(),
                $this->getPrice(),
                $this->getPromotionOrder(),
                $this->getOrder(),
                $this->getRank(),
                $this->getId()
            )
        ];
    }

    /**
     * @return array
     */
    public function getValueForCollapse(): array
    {
        return [
            array_merge(
                $this->getScript(),
                $this->getPrice(),
                $this->getIsGroupPrimary(),
                $this->getPromotionOrder(),
                $this->getOrder(),
                $this->getRank(),
                $this->getId()
            )
        ];
    }

    /**
     * @inerhitDoc
     * @param Collection $data
     * @return int
     */
    public static function getOrderInGroup(Collection $data): Collection
    {
        return self::calcOrderInGroup($data, [
            ['script', 'asc'],
            ['price', 'asc'],
            ['is_group_primary', 'desc'],
            ['order', 'asc'],
            ['rank', 'desc'],
            ['id', 'desc'],
        ]);
    }
}

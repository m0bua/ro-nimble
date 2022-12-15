<?php
/**
 * Класс для генерации сортировки по рейтингу
 * Class RankComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

use Illuminate\Support\Collection;

class RankComponent extends BaseSortComponent
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
                'params' => [
                    'is_rozetka_top' => $this->filters->category->isRozetkaTop()
                ],
                'source' => <<< EOF
                        if (doc['price'].value == 0) {
                            return 99999;
                        }

                        int sell_status = 1;
                        int state = 1;
                        int seller = 1;

                        if (doc['sell_status'].value == 'available' || doc['sell_status'].value == 'limited') {
                            sell_status = 1;
                        } else if (doc['sell_status'].value == 'waiting_for_supply') {
                            sell_status = 15;
                        } else if (doc['sell_status'].value == 'out_of_stock') {
                            sell_status = 16;
                        } else if (doc['sell_status'].value == 'unavailable') {
                            sell_status = 17;
                        }

                        if (doc['state'].value == 'new') {
                            state = 1;
                        } else if (doc['state'].value == 'refurbished') {
                            state = 3;
                        } else if (doc['state'].value == 'used') {
                            state = 7;
                        }

                        if (params.is_rozetka_top && doc['seller_id'].value != 5) {
                            seller = 2;
                        }

                        return sell_status * state * seller;
                    EOF
            ],
            'order' => 'asc'
        ]
        ];
    }

    protected static function getScriptOrder(\stdClass $product): int
    {
        if (0 == $product->price) {
            return 99999;
        }

        $sellStatus = $state = $seller = 1;
        switch ($product->sell_status) {
            case 'waiting_for_supply':
                $sellStatus = 15;
                break;
            case 'out_of_stock':
                $sellStatus = 16;
                break;
            case 'unavailable':
                $sellStatus = 17;
                break;
        }

        switch ($product->state) {
            case 'used':
                $state = 7;
                break;
            case 'refurbished':
                $state = 3;
                break;
        }

        if ($product->is_rozetka_top && $product->seller_id !== 5) {
            $seller = 2;
        }

        return $sellStatus * $state * $seller;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return [
            array_merge(
                $this->getScript(),
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
     * @return Collection
     */
    public static function getOrderInGroup(Collection $data): Collection
    {
        return self::calcOrderInGroup($data, [
            ['script', 'asc'],
            ['is_group_primary', 'desc'],
            ['order', 'asc'],
            ['rank', 'desc'],
            ['id', 'desc'],
        ]);
    }
}

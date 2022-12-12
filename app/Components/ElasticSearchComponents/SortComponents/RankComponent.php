<?php
/**
 * Класс для генерации сортировки по рейтингу
 * Class RankComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

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
     * @param array $primary
     * @param array $secondary
     * @return array
     */
    public function getMainProductBySort(array $primary, array $secondary): array
    {
        $primary['primary'] = 1;
        $secondary['primary'] = 0;
        $mergedArray[] = $primary;
        $mergedArray[] = $secondary;

        usort($mergedArray, fn (array $prime, array $second): int =>
            ($prime['weight_sort'] <=> $second['weight_sort']) * 10000 + // scripted field ASC
            ($second['primary'] <=> $prime['primary']) * 1000 + // primary DESC
            ($prime['order'] <=> $second['order']) * 100 + // order ASC
            ($second['rank'] <=> $prime['rank']) * 10 + // rank DESC
            ($second['id'] <=> $prime['id']) // id DESC
        );

        return $mergedArray[0];
    }

    /**
     * @param array $data
     * @param bool $isPromotion
     * @return array
     */
    public function sortResultArray(array $data, bool $isPromotion): array
    {
        if ($isPromotion) {
            usort($data, fn (array $prime, array $second): int =>
                ($prime['weight_sort'] <=> $second['weight_sort']) * 10000 + // scripted field ASC
                ($prime['promotion_order'] <=> $second['promotion_order']) * 1000 + // promotion order ASC
                ($prime['order'] <=> $second['order']) * 100 + // order ASC
                ($second['rank'] <=> $prime['rank']) * 10 + // rank DESC
                ($second['id'] <=> $prime['id']) // id DESC
            );
        } else {
            usort($data, fn (array $prime, array $second): int =>
                ($prime['weight_sort'] <=> $second['weight_sort']) * 1000 + // scripted field ASC
                ($prime['order'] <=> $second['order']) * 100 + // order ASC
                ($second['rank'] <=> $prime['rank']) * 10 + // rank DESC
                ($second['id'] <=> $prime['id']) // id DESC
            );
        }

        return $data;
    }
}

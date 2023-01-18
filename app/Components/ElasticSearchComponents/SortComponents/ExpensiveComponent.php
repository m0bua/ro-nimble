<?php
/**
 * Класс для генерации сортировки по "От дорогих к дешевым"
 * Class ExpensiveComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

class ExpensiveComponent extends BaseSortComponent
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
     * @return array[]
     */
    private function getPrice(): array
    {
        return [
            'price' => [
                'order' => 'desc'
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
            ($prime['weight_sort'] <=> $second['weight_sort']) * 100000 + // scripted field ASC
            ($second['price'] <=> $prime['price']) * 10000 + // price DESC
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
    public function sortResultArray(array $data, bool $isPromotion = false): array
    {
        if ($isPromotion) {
            usort($data, fn (array $prime, array $second): int =>
                ($prime['weight_sort'] <=> $second['weight_sort']) * 100000 + // scripted field ASC
                ($second['price'] <=> $prime['price']) * 10000 + // price DESC
                ($prime['promotion_order'] <=> $second['promotion_order']) * 1000 + // promotion order ASC
                ($prime['order'] <=> $second['order']) * 100 + // order ASC
                ($second['rank'] <=> $prime['rank']) * 10 + // rank DESC
                ($second['id'] <=> $prime['id']) // id DESC
            );
        } else {
            usort($data, fn (array $prime, array $second): int =>
                ($prime['weight_sort'] <=> $second['weight_sort']) * 1000 + // scripted field ASC
                ($second['price'] <=> $prime['price']) * 1000 + // price DESC
                ($prime['order'] <=> $second['order']) * 100 + // order ASC
                ($second['rank'] <=> $prime['rank']) * 10 + // rank DESC
                ($second['id'] <=> $prime['id']) // id DESC
            );
        }

        return $data;
    }
}

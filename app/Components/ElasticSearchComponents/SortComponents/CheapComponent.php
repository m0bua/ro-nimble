<?php
/**
 * Класс для генерации сортировки по "От дешевым к дорогим"
 * Class CheapComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

class CheapComponent extends BaseSortComponent
{
    /**
     * @return array[]
     */
    protected function getScript(): array
    {
        return [
            'estimated_weight.value' => [
                'order' => 'asc',
                'nested' => [
                    'path' => 'estimated_weight',
                    'filter' => [
                        'term' => [
                            'estimated_weight.sort' => 'price'
                        ]
                    ]
                ]
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
            ($prime['price'] <=> $second['price']) * 10000 + // price ASC
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
                ($prime['price'] <=> $second['price']) * 10000 + // price ASC
                ($prime['promotion_order'] <=> $second['promotion_order']) * 1000 + // promotion order ASC
                ($prime['order'] <=> $second['order']) * 100 + // order ASC
                ($second['rank'] <=> $prime['rank']) * 10 + // rank DESC
                ($second['id'] <=> $prime['id']) // id DESC
            );
        } else {
            usort($data, fn (array $prime, array $second): int =>
                ($prime['weight_sort'] <=> $second['weight_sort']) * 10000 + // scripted field ASC
                ($prime['price'] <=> $second['price']) * 1000 + // price ASC
                ($prime['order'] <=> $second['order']) * 100 + // order ASC
                ($second['rank'] <=> $prime['rank']) * 10 + // rank DESC
                ($second['id'] <=> $prime['id']) // id DESC
            );
        }

        return $data;
    }
}

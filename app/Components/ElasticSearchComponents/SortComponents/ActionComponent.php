<?php
/**
 * Класс для генерации сортировки по "Акциям"
 * Class ActionComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

class ActionComponent extends BaseSortComponent
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
                $this->getOrder(),
                $this->getRank(),
                $this->getId()
            )
        ];
    }
}

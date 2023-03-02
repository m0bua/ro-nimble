<?php
/**
 * Класс для генерации сортировки по "Новинкам"
 * Class NoveltyComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

class NoveltyComponent extends BaseSortComponent
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

                        int sell_status_rate = 1;
                        int label_rate = 2;

                        for (int label_id : doc['goods_labels_ids']) {
                            if (label_id == 2 || label_id == 24) {
                                label_rate = 1;
                            }
                        }

                        if (doc['sell_status'].value == 'available' || doc['sell_status'].value == 'limited') {
                            sell_status_rate = 1;
                        } else if (doc['sell_status'].value == 'waiting_for_supply') {
                            sell_status_rate = 3;
                        } else if (doc['sell_status'].value == 'out_of_stock') {
                            sell_status_rate = 4;
                        } else if (doc['sell_status'].value == 'unavailable') {
                            sell_status_rate = 5;
                        }

                        return sell_status_rate * label_rate;
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

    public function getMainProductBySort(array $primary, array $secondary, bool $isPromotion): array
    {
        // TODO: Implement getMainProductBySort() method.
        return [];
    }

    public function sortResultArray(array $data, bool $isPromotion): array
    {
        // TODO: Implement sortResultArray() method.
        return [];
    }
}

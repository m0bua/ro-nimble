<?php
/**
 * Класс для генерации сортировки по "По популяности"
 * Class PopularityComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

use App\Components\ElasticSearchComponents\BaseComponent;

class PopularityComponent extends BaseComponent
{
    public function getValue(): array
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
                            if (label_id == 1) {
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
            ],
            'rank' => [
                'order' => 'desc'
            ],
            'order' => [
                'order' => 'asc'
            ],
            'id' => [
                'order' => 'desc'
            ]
        ];
    }
}

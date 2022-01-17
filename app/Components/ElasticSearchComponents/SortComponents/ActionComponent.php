<?php
/**
 * Класс для генерации сортировки по "Акциям"
 * Class ActionComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;


use App\Components\ElasticSearchComponents\BaseComponent;

class ActionComponent extends BaseComponent
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

                        int sell_status = 1;
                        int tag = 2;

                        for (int tag : doc['tags']) {
                            if (tag == 4) {
                                tag = 1;
                            }
                        }

                        if (doc['sell_status'].value == 'available' || doc['sell_status'].value == 'limited') {
                            sell_status = 1;
                        } else if (doc['sell_status'].value == 'waiting_for_supply') {
                            sell_status = 3;
                        } else if (doc['sell_status'].value == 'out_of_stock') {
                            sell_status = 4;
                        } else if (doc['sell_status'].value == 'unavailable') {
                            sell_status = 5;
                        }

                        return sell_status * tag;
                    EOF
                ],
                'order' => 'asc'
            ],
            'price' => [
                'order' => 'desc'
            ],
            'rank' => [
                'order' => 'desc'
            ],
            'id' => [
                'order' => 'desc'
            ]
        ];
    }
}

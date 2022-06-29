<?php
/**
 * Класс для генерации сортировки по рейтингу
 * Class RankComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

use App\Components\ElasticSearchComponents\BaseComponent;

class RankComponent extends BaseComponent
{
    public function getValue(): array
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
            ],
            'order' => [
                'order' => 'asc'
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

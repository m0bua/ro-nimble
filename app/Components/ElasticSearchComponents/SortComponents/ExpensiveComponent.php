<?php
/**
 * Класс для генерации сортировки по "От дорогих к дешевым"
 * Class ExpensiveComponent
 * @package App\Components\ElasticSearchComponents\SortComponents
 */

namespace App\Components\ElasticSearchComponents\SortComponents;

use App\Components\ElasticSearchComponents\BaseComponent;

class ExpensiveComponent extends BaseComponent
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

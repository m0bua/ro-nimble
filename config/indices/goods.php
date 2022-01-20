<?php

return [
    'settings' => [
        'number_of_replicas' => 1,
        'number_of_shards' => 6,
        'codec' => 'best_compression',
    ],
    'mappings' => [
        '_source' => ['enabled' => true],
        'properties' => [
            'id' => [
                'type' => 'long',
            ],
            'promotion_ids' => [
                'type' => 'integer'
            ],
            'category_id' => [
                'type' => 'integer',
            ],
            'categories_path' => [
                'type' => 'integer'
            ],
            'group_id' => [
                'type' => 'integer',
            ],
            'is_group_primary' => [
                'type' => 'integer',
            ],
            'option_checked' => [
                'type' => 'integer',
            ],
            'option_sliders' => [
                'type' => 'nested',
                'properties' => [
                    'id' => [
                        'type' => 'integer',
                    ],
                    'value' => [
                        'type' => 'double',
                    ]
                ]
            ],
            'option_values' => [
                'type' => 'integer',
            ],
            'options' => [
                'type' => 'integer',
            ],
            'bonus_charge_pcs' => [
                'type' => 'integer',
            ],
            'price' => [
                'type' => 'integer'
            ],
            'producer_id' => [
                'type' => 'integer',
            ],
            'rank' => [
                'type' => 'float'
            ],
            'sell_status' => [
                'type' => 'keyword'
            ],
            'seller_id' => [
                'type' => 'integer'
            ],
            'merchant_type' => [
                'type' => 'integer',
            ],
            'series_id' => [
                'type' => 'integer'
            ],
            'state' => [
                'type' => 'keyword'
            ],
            'status_inherited' => [
                'type' => 'keyword'
            ],
            'tags' => [
                'type' => 'integer'
            ],
            'country_code' => [
                'type' => 'keyword'
            ],
            'group_token' => [
                'type' => 'keyword'
            ],
            'payment_method_ids' => [
                'type' => 'integer'
            ],
            'car_trim_id' => [
                'type' => 'integer'
            ],
            'car_brand_id' => [
                'type' => 'integer'
            ],
            'car_model_id' => [
                'type' => 'integer'
            ],
            'car_year_id' => [
                'type' => 'integer'
            ],
        ]
    ],
];

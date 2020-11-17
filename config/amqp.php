<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Define which configuration should be used
    |--------------------------------------------------------------------------
    */

    'use' => 'default',

    /*
    |--------------------------------------------------------------------------
    | AMQP properties separated by key
    |--------------------------------------------------------------------------
    */

    'properties' => [

        'default' => [

        ],

        // marketing service
        'ms' => [
            'host'                  => env('AMQP_MS_HOST', 'localhost'),
            'port'                  => env('AMQP_MS_PORT', 5672),
            'username'              => env('AMQP_MS_USERNAME', ''),
            'password'              => env('AMQP_MS_PASSWORD', ''),
            'vhost'                 => '/',
            'connect_options'       => [
                'heartbeat' => 0
            ],
            'ssl_options'           => [],

            'exchange'              => env('AMQP_MS_EXCHANGE', 'promo.goods'),
            'exchange_type'         => 'topic',
            'exchange_passive'      => false,
            'exchange_durable'      => true,
            'exchange_auto_delete'  => false,
            'exchange_internal'     => false,
            'exchange_nowait'       => false,
            'exchange_properties'   => [],

            'queue_force_declare'   => false,
            'queue_passive'         => false,
            'queue_durable'         => true,
            'queue_exclusive'       => false,
            'queue_auto_delete'     => false,
            'queue_nowait'          => false,
            'queue_properties'      => [
                'x-ha-policy' => ['S', 'all'],
//                'x-max-priority' => ['I', 5],
            ],

            'consumer_tag'          => '',
            'consumer_no_local'     => false,
            'consumer_no_ack'       => false,
            'consumer_exclusive'    => false,
            'consumer_nowait'       => false,
            'timeout'               => 0,
            'persistent'            => true,

            'qos'                   => true,
            'qos_prefetch_size'     => null,
            'qos_prefetch_count'    => 1000,
            'qos_a_global'          => false,

            'processor_name'        => function($routingKey) {
                return 'MarketingService\\' . ucfirst(
                    str_replace(
                        '_', '', str_replace(
                            '_record', '_Processor', str_replace(
                                '.', '_', $routingKey
                            )
                        )
                    )
                );
            }
        ],

        // goods service
        'gs' => [
            'host'                  => env('AMQP_GS_HOST', 'localhost'),
            'port'                  => env('AMQP_GS_PORT', 5672),
            'username'              => env('AMQP_GS_USERNAME', ''),
            'password'              => env('AMQP_GS_PASSWORD', ''),
            'vhost'                 => '/',
            'connect_options'       => [
                'heartbeat' => 0
            ],
            'ssl_options'           => [],

            'exchange'              => env('AMQP_GS_EXCHANGE', 'promo.goods'),
            'exchange_type'         => 'topic',
            'exchange_passive'      => false,
            'exchange_durable'      => true,
            'exchange_auto_delete'  => false,
            'exchange_internal'     => false,
            'exchange_nowait'       => false,
            'exchange_properties'   => [],

            'queue_force_declare'   => false,
            'queue_passive'         => false,
            'queue_durable'         => true,
            'queue_exclusive'       => false,
            'queue_auto_delete'     => false,
            'queue_nowait'          => false,
            'queue_properties'      => ['x-ha-policy' => ['S', 'all']],

            'consumer_tag'          => '',
            'consumer_no_local'     => false,
            'consumer_no_ack'       => false,
            'consumer_exclusive'    => false,
            'consumer_nowait'       => false,
            'timeout'               => 0,
            'persistent'            => true,

            'qos'                   => true,
            'qos_prefetch_size'     => 0,
            'qos_prefetch_count'    => 1,
            'qos_a_global'          => false,
            'processor_name'        => function($routingKey) {
                $keywords = explode('.', ucwords($routingKey, '.'));
                $thirdPart = str_replace('_', '', ucwords($keywords[2], '_'));

                return "GoodsService\\{$keywords[0]}{$keywords[1]}{$thirdPart}Processor";
            }
        ],

    ],

];

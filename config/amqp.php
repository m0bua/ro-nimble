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

        'default' => [],

        'local' => [
            'host'                  => 'localhost',
            'port'                  => 5672,
            'username'              => 'guest',
            'password'              => 'guest',
            'vhost'                 => '/',
            'connect_options'       => [
                'heartbeat' => 0
            ],
            'ssl_options'           => [],

            'exchange'              => 'amq.topic',
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

            'processor_name'        => [App\Processors\MarketingService\Support\ProcessorClassnameResolver::class, 'resolve'],
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
            'qos_prefetch_count'    => 10,
            'qos_a_global'          => false,
            'processor_name'        => [App\Processors\GoodsService\Support\ProcessorClassnameResolver::class, 'resolve'],
        ],

        // payment service
        'ps' => [
            'host'                  => env('AMQP_PS_HOST', 'localhost'),
            'port'                  => env('AMQP_PS_PORT', 5672),
            'username'              => env('AMQP_PS_USERNAME', ''),
            'password'              => env('AMQP_PS_PASSWORD', ''),
            'vhost'                 => '/',
            'connect_options'       => [
                'heartbeat' => 0
            ],
            'ssl_options'           => [],

            'exchange'              => env('AMQP_PS_EXCHANGE'),
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
            'processor_name'        => [App\Processors\PaymentService\Support\ProcessorClassnameResolver::class, 'resolve'],
        ],

        // bonus service
        'bs' => [
            'host'                  => env('AMQP_BS_HOST', 'localhost'),
            'port'                  => env('AMQP_BS_PORT', 5672),
            'username'              => env('AMQP_BS_USERNAME', ''),
            'password'              => env('AMQP_BS_PASSWORD', ''),
            'vhost'                 => '/',
            'connect_options'       => [
                'heartbeat' => 0
            ],
            'ssl_options'           => [],

            'exchange'              => env('AMQP_BS_EXCHANGE'),
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
            'processor_name'        => [App\Processors\BonusService\Support\ProcessorClassnameResolver::class, 'resolve'],
        ],
    ],

];

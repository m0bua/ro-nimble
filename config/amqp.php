<?php

$default = [
    'host'                  => 'localhost',
    'port'                  => 5672,
    'username'              => '',
    'password'              => '',
    'vhost'                 => '/',
    'connect_options'       => [
        'heartbeat' => 0
    ],
    'ssl_options'           => [],

    'exchange'              => '',
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
//        'x-max-priority' => ['I', 5],
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
];

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
        'default' => $default,

        // marketing service
        'ms' => array_merge($default, [
            'host'                  => env('AMQP_MS_HOST', 'localhost'),
            'port'                  => env('AMQP_MS_PORT', 5672),
            'username'              => env('AMQP_MS_USERNAME', ''),
            'password'              => env('AMQP_MS_PASSWORD', ''),
            'processor_name'        => [App\Processors\MarketingService\Support\ProcessorClassnameResolver::class, 'resolve'],
            'qos_prefetch_count'    => 1,
        ]),

        // goods service
        'gs' => array_merge($default, [
            'host'                  => env('AMQP_GS_HOST', 'localhost'),
            'port'                  => env('AMQP_GS_PORT', 5672),
            'username'              => env('AMQP_GS_USERNAME', ''),
            'password'              => env('AMQP_GS_PASSWORD', ''),
            'processor_name'        => [App\Processors\GoodsService\Support\ProcessorClassnameResolver::class, 'resolve'],
        ]),

        // payment service
        'ps' => array_merge($default, [
            'host'                  => env('AMQP_PS_HOST', 'localhost'),
            'port'                  => env('AMQP_PS_PORT', 5672),
            'username'              => env('AMQP_PS_USERNAME', ''),
            'password'              => env('AMQP_PS_PASSWORD', ''),
            'processor_name'        => [App\Processors\PaymentService\Support\ProcessorClassnameResolver::class, 'resolve'],
        ]),

        // bonus service
        'bs' => array_merge($default, [
            'host'                  => env('AMQP_BS_HOST', 'localhost'),
            'port'                  => env('AMQP_BS_PORT', 5672),
            'username'              => env('AMQP_BS_USERNAME', ''),
            'password'              => env('AMQP_BS_PASSWORD', ''),
            'processor_name'        => [App\Processors\BonusService\Support\ProcessorClassnameResolver::class, 'resolve'],
        ]),

        // market enterprise
        'me' => array_merge($default, [
            'host'                  => env('AMQP_ME_HOST', 'localhost'),
            'port'                  => env('AMQP_ME_PORT', 5672),
            'username'              => env('AMQP_ME_USERNAME', ''),
            'password'              => env('AMQP_ME_PASSWORD', ''),
            'processor_name'        => [App\Processors\MarketEnterprise\Support\ProcessorClassnameResolver::class, 'resolve'],
        ]),

        // market enterprise
        'msl' => array_merge($default, [
            'host'                  => env('AMQP_MSL_HOST', 'localhost'),
            'port'                  => env('AMQP_MSL_PORT', 5672),
            'username'              => env('AMQP_MSL_USERNAME', ''),
            'password'              => env('AMQP_MSL_PASSWORD', ''),
            'processor_name'        => [App\Processors\MarketingService\Support\LabelProcessorClassnameResolver::class, 'resolve'],
        ]),

        'local' => [
            'host'                  => env('AMQP_LOCAL_HOST', 'localhost'),
            'port'                  => env('AMQP_LOCAL_PORT', 5672),
            'username'              => env('AMQP_LOCAL_USERNAME', ''),
            'password'              => env('AMQP_LOCAL_PASSWORD', ''),
            'exchange'              => env('AMQP_LOCAL_EXCHANGE', ''),
            'vhost'                 => env('AMQP_LOCAL_VHOST', '/'),
            'qos_prefetch_count'    => 100,
        ],
    ],

];

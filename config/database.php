<?php
return [
    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],
    ],
    'elasticsearch' => [
        'hosts' => explode(',', env('ELASTIC_HOSTS')),
        'basic_auth' => [
            'username' => env('ELASTIC_AUTH_USER'),
            'password' => env('ELASTIC_AUTH_PASS'),
        ]
    ]
];

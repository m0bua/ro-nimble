<?php

return [
    'migrations' => 'migrations',
    'default' => env('DB_CONNECTION', 'nimble'),
    'connections' => [
        'nimble' => [
            'read' => [
                'host' => env('DB_HOST_READ', 'localhost'),
                'port' => env('DB_PORT_READ', 5432),
            ],
            'write' => [
                'host' => env('DB_HOST', 'localhost'),
                'port' => env('DB_PORT', 5432),
            ],
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'postgres'),
            'username' => env('DB_USERNAME', 'postgres'),
            'password' => env('DB_PASSWORD', 'postgres'),
            'prefix'   => '',
            'schema'   => 'public',
            'sslmode'  => 'prefer',
            'options'  => [
                PDO::ATTR_EMULATE_PREPARES => true,
            ],
        ],
        'store' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_STORE_HOST', 'localhost'),
            'database' => env('DB_STORE_DATABASE', 'postgres'),
            'port'     => env('DB_STORE_PORT', 5432),
            'username' => env('DB_STORE_USERNAME', 'postgres'),
            'password' => env('DB_STORE_PASSWORD', 'postgres'),
            'prefix'   => '',
            'schema'   => 'public',
            'sslmode'  => 'prefer',
            'options'  => [
                PDO::ATTR_EMULATE_PREPARES => true
            ],
        ],
    ],
    'redis' => [
        'client' => env('REDIS_CLIENT', 'predis'),
        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
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
    ],
];

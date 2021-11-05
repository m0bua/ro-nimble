<?php

use App\Cores\ConsumerCore\Loggers\ConsumerErrorLogger;
use App\Cores\ConsumerCore\Loggers\ConsumerInfoLogger;
use App\Logging\DefaultLogger;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    'default' => env('LOG_CHANNEL', 'stack'),
    'channels' => [

        'default' => [
            'driver' => 'daily',
            'tap' => [DefaultLogger::class],
            'path' => storage_path('logs/lumen.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'consumer_message' => [
            'driver' => 'monolog',
            'handler' => App\Logging\Handlers\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/consumer-messages.log'),
                'maxFiles' => 7, // days
            ],
            'tap' => [ConsumerInfoLogger::class],
        ],

        'db_queries' => [
            'driver' => 'daily',
            'tap' => [DefaultLogger::class],
            'path' => storage_path('logs/db-queries.log'),
            'days' => 3
        ],

        'consumer_error_message' => [
            'driver' => 'daily',
            'tap' => [ConsumerErrorLogger::class],
            'path' => storage_path('logs/lumen.log'),
            'days' => 14,
        ],

        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/lumen.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/lumen.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/lumen.log'),
        ],
    ],
];

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
            'driver' => 'daily',
            'tap' => [ConsumerInfoLogger::class],
            'path' => storage_path('logs/consumer-messages.log'),
            'days' => 7,
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
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/lumen.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/lumen.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Lumen Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],
    ],
];

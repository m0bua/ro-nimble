<?php

namespace App\Logging;

use Illuminate\Support\Facades\Log;
use Monolog\Formatter\LineFormatter;

class ConsumerLogger
{
    /**
     * @param $logger
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter("%message%\n"));
            $handler->setFormatter(new LineFormatter(
                '{"datetime":"%datetime%","message":"%message%","context":%context%}' . PHP_EOL
            ));
        }
    }

    /**
     * @param string $message
     * @param string $config
     * @param array $context
     */
    public static function logMessage(string $message, string $config, array $context = [])
    {
        $loggingConfig = self::config();

        if (array_key_exists($config, $loggingConfig) && $loggingConfig[$config]) {
            Log::channel('consumer_message')->info($message, $context);
        }
    }

    /**
     *
     */
    private static function config()
    {
        return [
            'ms' => true,
            'gs' => false
        ];
    }
}

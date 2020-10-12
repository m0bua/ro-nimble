<?php

namespace App\Cores\ConsumerCore\Loggers;

use App\Cores\ConsumerCore\Config;
use App\Cores\ConsumerCore\Interfaces\MessageLoggerInterface;

use App\Traits\DefaultLogFormatterTrait;
use Illuminate\Support\Facades\Log;

class ConsumerErrorLogger implements MessageLoggerInterface
{
    use DefaultLogFormatterTrait;

    public function __invoke($logger)
    {
        $this->defaultFormat($logger);
    }

    public static function log(string $message, string $config, array $context = [])
    {
        $loggingConfig = Config::logErrorMessages();

        if (array_key_exists($config, $loggingConfig) && $loggingConfig[$config]) {
            Log::channel('consumer_error_message')->error($message, $context);
        }
    }
}

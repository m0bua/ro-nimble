<?php

namespace App\Traits;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;

trait DefaultLogFormatterTrait
{
    /**
     * @param Logger $logger
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpMissingParamTypeInspection
     */
    public function defaultFormat($logger)
    {
        $formatter = new LineFormatter(
            '{"datetime":"%datetime%","level":"%level_name%","message":"%message%","context":%context%}' . PHP_EOL
        );

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
        }
    }
}

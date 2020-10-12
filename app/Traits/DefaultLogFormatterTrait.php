<?php


namespace App\Traits;


use Monolog\Formatter\LineFormatter;

trait DefaultLogFormatterTrait
{
    public function defaultFormat($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                '{"datetime":"%datetime%","message":"%message%","context":%context%}' . PHP_EOL
            ));
        }
    }
}

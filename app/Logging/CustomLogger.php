<?php


namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class CustomLogger
{

    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(
//                '[%datetime%] %channel%.%level_name%: %message% %context% %extra%'
                new LineFormatter("%message%\n")
            );
        }
    }

    public static function generateMessage(\Throwable $t, array $additionalData = []): string
    {
        return json_encode(
            array_merge(
                [
                    'datetime' => date('Y-m-d\TH:i:sP'),
                    'error_message' => $t->getMessage(),
                    'file' => $t->getFile(),
                    'line' => $t->getLine()
                ],
                $additionalData
            )
        );
    }
}

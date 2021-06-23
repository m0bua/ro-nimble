<?php

namespace App\Processors\MarketingService\Support;

class Helpers
{
    public const PROCESSOR_NAMESPACE = 'MarketingService\\';

    /**
     * Resolves processor class name from RabbitMQ routing key
     *
     * @param string $routingKey
     * @return string
     */
    public static function resolveProcessorClassname(string $routingKey): string
    {
        return self::PROCESSOR_NAMESPACE . ucfirst(
                str_replace(
                    '_', '', str_replace(
                        '_record', '_Processor', str_replace(
                            '.', '_', $routingKey
                        )
                    )
                )
            );
    }
}

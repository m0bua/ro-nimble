<?php

namespace App\Processors\MarketingService\Support;

class ProcessorClassnameResolver
{
    public const PROCESSOR_NAMESPACE = 'MarketingService\\';

    /**
     * @param string $routingKey
     * @return string
     */
    public function __invoke(string $routingKey): string
    {
        return self::resolve($routingKey);
    }

    /**
     * Resolves processor class name from RabbitMQ routing key
     *
     * @param string $routingKey
     * @return string
     */
    public static function resolve(string $routingKey): string
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

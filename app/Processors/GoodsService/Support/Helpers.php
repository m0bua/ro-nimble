<?php

namespace App\Processors\GoodsService\Support;

class Helpers
{
    public const PROCESSOR_NAMESPACE = 'GoodsService\\';

    /**
     * Resolves processor class name from RabbitMQ routing key
     *
     * @param string $routingKey
     * @return string
     */
    public static function resolveProcessorClassname(string $routingKey): string
    {
        $namespace = self::PROCESSOR_NAMESPACE;
        $keywords = explode('.', ucwords($routingKey, '.'));
        $thirdPart = str_replace('_', '', ucwords($keywords[2], '_'));

        return "$namespace$keywords[0]$keywords[1]{$thirdPart}Processor";
    }
}

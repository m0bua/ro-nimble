<?php

namespace App\Processors\PaymentService;

use Illuminate\Support\Str;

class Helpers
{
    public const ROUTING_SEPARATOR = '.';
    public const PROCESSOR_NAMESPACE = 'PaymentService\\';

    /**
     * Resolves processor class name from RabbitMQ routing key
     *
     * @param string $routingKey
     * @return string
     */
    public static function resolveProcessorClassname(string $routingKey): string
    {
        $namespace = self::PROCESSOR_NAMESPACE;
        $method = Str::before($routingKey, self::ROUTING_SEPARATOR);
        $entity = Str::after($routingKey, self::ROUTING_SEPARATOR);

        $preparedMethod = Str::studly($method);
        $preparedEntity = Str::replace('.all', '', $entity);
        $preparedEntity = Str::studly($preparedEntity);

        $modifier = Str::after($entity, self::ROUTING_SEPARATOR);
        $modifier = Str::studly($modifier);
        if ($modifier === $preparedEntity) {
            $modifier = '';
        }

        return "$namespace$preparedEntity\\$preparedMethod{$modifier}Processor";
    }
}

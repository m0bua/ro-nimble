<?php

namespace App\Processors\PaymentService\Support;

use Illuminate\Support\Str;

class ProcessorClassnameResolver
{
    public const ROUTING_SEPARATOR = '.';
    public const PROCESSOR_NAMESPACE = 'PaymentService';
    public const SUFFIXES = [
        '.all', '.Catalog'
    ];

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
     * @noinspection DuplicatedCode
     */
    public static function resolve(string $routingKey): string
    {
        $namespace = self::PROCESSOR_NAMESPACE;
        $method = Str::before($routingKey, self::ROUTING_SEPARATOR);
        $entity = Str::after($routingKey, self::ROUTING_SEPARATOR);

        $preparedMethod = Str::studly($method);
        $preparedEntity = Str::before($entity, self::ROUTING_SEPARATOR); // cutting all suffixes
        $preparedEntity = Str::studly($preparedEntity); // snake_case => PascalCase

        if (!Str::endsWith($entity, self::SUFFIXES) && $preparedEntity !== Str::studly($entity)) {
            return '';
        }

        return "$namespace\\$preparedEntity\\{$preparedMethod}EventProcessor";
    }
}

<?php

namespace App\Processors\MarketingService\Support;

use Illuminate\Support\Str;

class ProcessorClassnameResolver
{
    public const PROCESSOR_NAMESPACE = 'MarketingService\\';
    public const ROUTING_SEPARATOR = '.';
    public const CHANGE_EVENT = 'change';

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
        [$event, $entity] = explode(self::ROUTING_SEPARATOR, $routingKey);

        $preparedEvent = $event === self::CHANGE_EVENT ? 'Upsert' : Str::studly($event);
        $preparedEntity = Str::studly($entity);

        return self::PROCESSOR_NAMESPACE . "$preparedEntity\\{$preparedEvent}EventProcessor";
    }
}

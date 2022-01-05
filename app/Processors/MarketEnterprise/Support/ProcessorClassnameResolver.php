<?php

namespace App\Processors\MarketEnterprise\Support;

use Illuminate\Support\Str;

class ProcessorClassnameResolver
{
    public const ROUTING_SEPARATOR = '.';
    public const PROCESSOR_NAMESPACE = 'MarketEnterprise';

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
        $namespace = self::PROCESSOR_NAMESPACE;
        $keywords = explode(self::ROUTING_SEPARATOR, $routingKey);
        if (count($keywords) < 3) {
            return '';
        }

        $event = $keywords[0] ?? '';
        $entity = $keywords[1] ?? '';
        $relation = $keywords[2] ?? '';

        if (!$event || !$entity || !$relation) {
            return '';
        }

        $preparedEvent = in_array($event, ['create', 'update'], true) ? 'Upsert' : Str::studly($event);
        $preparedEntity = Str::studly($entity);
        $preparedRelation = Str::studly($relation);

        return "$namespace\\$preparedEntity$preparedRelation\\{$preparedEvent}EventProcessor";
    }
}

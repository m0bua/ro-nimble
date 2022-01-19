<?php

namespace App\Processors\MarketingService\Support;

use Illuminate\Support\Str;

class LabelProcessorClassnameResolver
{
    public const PROCESSOR_NAMESPACE = 'MarketingService\\Labels\\';
    public const ROUTING_SEPARATOR = '.';

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
        $keywords = explode(self::ROUTING_SEPARATOR, $routingKey);
        if (count($keywords) < 3) {
            return '';
        }

        $event = $keywords[0] ?? '';
        $entity = $keywords[1] ?? '';

        if (!$event || !$entity) {
            return '';
        }

        $preparedEvent = in_array($event, ['create', 'update'], true) ? 'Upsert' : Str::studly($event);
        $preparedEntity = Str::studly($entity);

        return self::PROCESSOR_NAMESPACE . "$preparedEntity\\{$preparedEvent}EventProcessor";
    }
}

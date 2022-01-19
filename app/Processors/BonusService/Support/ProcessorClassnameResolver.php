<?php

namespace App\Processors\BonusService\Support;

use Illuminate\Support\Str;

class ProcessorClassnameResolver
{
    public const ROUTING_SEPARATOR = '.';
    public const PROCESSOR_NAMESPACE = 'BonusService';

    public const CREATE_EVENT = 'create';
    public const CHANGE_EVENT = 'change';
    public const UPSERT_EVENTS = [
        self::CREATE_EVENT,
        self::CHANGE_EVENT,
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
        [$event, $entity] = explode(self::ROUTING_SEPARATOR, $routingKey);

        $preparedEvent = in_array($event, self::UPSERT_EVENTS, true) ? 'Upsert' : Str::studly($event);
        $preparedEntity = Str::studly($entity); // snake_case => PascalCase

        return self::PROCESSOR_NAMESPACE . "\\$preparedEntity\\{$preparedEvent}EventProcessor";
    }
}

<?php

namespace App\Processors\CommentService\Support;

use Illuminate\Support\Str;

class ProcessorClassnameResolver
{
    private const PROCESSOR_NAMESPACE = 'CommentService\\GoodsComments\\';
    private const ROUTING_SEPARATOR = '.';
    private const CHANGE_EVENT = 'change.totalComments';
    private const CREATE_EVENT = 'create.totalComments';
    private const DELETE_EVENT = 'delete.totalComments';

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
        $keyParts = explode(self::ROUTING_SEPARATOR, $routingKey);
        $event = $keyParts[0] . '.' . $keyParts[1];

        switch ($event) {
            case self::CREATE_EVENT:
            case self::CHANGE_EVENT:
                $preparedEvent = 'Upsert';
                break;
            case self::DELETE_EVENT:
                $preparedEvent = 'Delete';
                break;
            default:
                $preparedEvent = Str::studly($event);
        }

        return self::PROCESSOR_NAMESPACE . "{$preparedEvent}CommentProcessor";
    }
}

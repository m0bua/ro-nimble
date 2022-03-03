<?php

namespace App\Services\Indexers;

use PhpAmqpLib\Message\AMQPMessage;

interface Indexer
{
    /**
     * Handle and index message from queue
     *
     * @param AMQPMessage $message
     * @return void
     */
    public function handleMessage(AMQPMessage $message): void;
}

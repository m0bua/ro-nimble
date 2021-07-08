<?php

namespace App\Cores\ConsumerCore\Interfaces;

interface ProcessorInterface
{
    /**
     * Proceed with AMQ Message
     *
     * @param MessageInterface $message
     * @return int
     */
    public function processMessage(MessageInterface $message): int;
}

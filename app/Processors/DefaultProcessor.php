<?php


namespace App\Processors;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;

class DefaultProcessor extends AbstractProcessor
{
    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        return Codes::SKIP;
    }
}

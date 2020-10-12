<?php

namespace App\Cores\ConsumerCore\Interfaces;

interface ProcessorInterface
{
    public function processMessage(MessageInterface $message): int;
}

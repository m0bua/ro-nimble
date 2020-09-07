<?php

namespace App\Processors;

use App\Interfaces\MessageInterface;
use App\Interfaces\ProcessorInterface;

abstract class AbstractCore implements ProcessorInterface
{

    /**
     * @var MessageInterface
     */
    protected MessageInterface $message;

    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    final public function run()
    {
        return $this->doJob();
    }

    /**
     * @return mixed
     */
    abstract public function doJob();
}

<?php

namespace App\Processors;

use App\Interfaces\MessageInterface;
use App\Interfaces\ProcessorInterface;

abstract class AbstractCore implements ProcessorInterface
{

    /**
     * @var MessageInterface
     */
    protected $message;

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

    abstract public function doJob();
}

<?php

namespace App\Processors;

use App\ValueObjects\Message;

abstract class AbstractCore
{

    /**
     * @var Message
     */
    protected $message;

    public function __construct(Message $message)
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
    abstract protected function doJob();
}

<?php

namespace App\Processors;

use App\ValueObjects\Message;

abstract class AbstractCore
{

    /**
     * @var Message
     */
    protected $message;

    /**
     * @return mixed
     */
    final public function run()
    {
        return $this->doJob();
    }

    /**
     * @param Message $message
     * @return $this
     */
    public function setMessage(Message $message): AbstractCore
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    abstract protected function doJob();
}

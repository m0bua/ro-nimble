<?php

namespace App\Consumers;

abstract class Consumer
{
    /**
     * @var string
     */
    protected $queueName;

    /**
     * @return mixed
     */
    abstract public function consume();
}

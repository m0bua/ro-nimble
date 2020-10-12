<?php

namespace App\Cores\ConsumerCore;

use Exception;

class ErrorMessage
{
    /**
     * @var string
     */
    private string $error;

    /**
     * ErrorMessage constructor.
     * @param string $error
     */
    public function __construct(string $error)
    {
        $this->error = $error;
    }

    /**
     * @throws Exception
     */
    public function throwException()
    {
        if ('' !== $this->error) {
            throw new Exception($this->error);
        }
    }
}

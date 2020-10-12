<?php

namespace App\Logging;

use App\Traits\DefaultLogFormatterTrait;

class DefaultLogger
{
    use DefaultLogFormatterTrait;

    public function __invoke($logger)
    {
        $this->defaultFormat($logger);
    }
}

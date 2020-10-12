<?php


namespace App\Logging;


use App\Interfaces\DefaultLogInterface;
use App\Traits\DefaultLogFormatterTrait;
use Illuminate\Support\Facades\Log;

class DefaultLogger
{
    use DefaultLogFormatterTrait;

    public function __invoke($logger)
    {
        $this->defaultFormat($logger);
    }
}

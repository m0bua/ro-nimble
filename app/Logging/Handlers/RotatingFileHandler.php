<?php

namespace App\Logging\Handlers;

use Monolog\Handler\RotatingFileHandler as BaseRotatingFileHandler;
use Monolog\Logger;

class RotatingFileHandler extends BaseRotatingFileHandler
{
    /**
     * @param string $filename
     * @param int $maxFiles The maximal amount of files to keep (0 means unlimited)
     * @param int $level
     * @param bool $bubble
     * @param int|null $filePermission Optional file permissions (default (0644) are only for owner read/write)
     * @param bool $useLocking Try to lock log file before doing any writes
     */
    public function __construct(string $filename, int $maxFiles = 0, $level = Logger::DEBUG, bool $bubble = true, ?int $filePermission = null, bool $useLocking = false)
    {
        parent::__construct($filename, $maxFiles, $level, $bubble, $filePermission, $useLocking);
    }

    /**
     * @inheritDoc
     */
    protected function write(array $record): void
    {
        $this->stream = null;
        parent::write($record);
    }
}

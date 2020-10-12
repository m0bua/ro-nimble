<?php


namespace App\Cores\ConsumerCore\Interfaces;


interface MessageLoggerInterface
{
    public function __invoke($logger);

    public static function log(string $message, string $config, array $context = []);
}

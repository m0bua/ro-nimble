<?php


namespace App\Cores\ConsumerCore;


class Config
{
    public static function logInfoMessages(): array
    {
        return [
            'ms' => true,
            'gs' => false,
            'ps' => false,
            'bs' => false,
            'me' => true,
        ];
    }

    public static function logErrorMessages(): array
    {
        return [
            'ms' => true,
            'gs' => true,
            'ps' => true,
            'bs' => true,
            'me' => true,
        ];
    }
}

<?php


namespace App\Cores\ConsumerCore;


class Config
{
    public static function logInfoMessages()
    {
        return [
            'ms' => true,
            'gs' => false,
        ];
    }

    public static function logErrorMessages()
    {
        return [
            'ms' => true,
            'gs' => true,
        ];
    }
}

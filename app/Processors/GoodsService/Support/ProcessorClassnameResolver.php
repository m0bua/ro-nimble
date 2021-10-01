<?php

namespace App\Processors\GoodsService\Support;

use App\Support\Language;
use Illuminate\Support\Str;

class ProcessorClassnameResolver
{
    public const PROCESSOR_NAMESPACE = 'GoodsService\\';

    public const SUPPORTED_LANGUAGES = [
        Language::UK,
        Language::RO,
        Language::EN,
        Language::UZ,
    ];

    /**
     * @param string $routingKey
     * @return string
     */
    public function __invoke(string $routingKey): string
    {
        return self::resolve($routingKey);
    }

    /**
     * Resolves processor class name from RabbitMQ routing key
     *
     * @param string $routingKey
     * @return string
     */
    public static function resolve(string $routingKey): string
    {
        $namespace = self::PROCESSOR_NAMESPACE;
        $keywords = explode('.', ucwords($routingKey, '.'));
        $thirdPart = str_replace('_', '', ucwords($keywords[2], '_'));

        $isItTranslation = Str::of($thirdPart)->lower()->endsWith(self::SUPPORTED_LANGUAGES);

        return $namespace . ($isItTranslation ? 'Translations\\' : '' ) . "$keywords[0]$keywords[1]{$thirdPart}Processor";
    }
}

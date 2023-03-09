<?php

namespace App\Processors\GoodsService\Support;

use App\Helpers\CountryHelper;
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
        $keywords = explode('.', ucwords($routingKey, '.'));
        $thirdPart = str_replace('_', '', ucwords($keywords[2], '_'));

        $event = in_array($keywords[0], ['Create', 'Change', 'Sync'], true) ? 'Upsert' : $keywords[0];
        $entity = $keywords[1];

        $isItTranslation = Str::of($thirdPart)->lower()->endsWith(self::SUPPORTED_LANGUAGES);
        if ($isItTranslation) {
            if (Str::length($thirdPart) > 2)  {
                $thirdPart = Str::substr($thirdPart, 0, -2);
            } else {
                $thirdPart = '';
            }

            return self::PROCESSOR_NAMESPACE . "Translations\\$entity$thirdPart\\{$event}EventProcessor";
        }

        $isItRegional = Str::of($thirdPart)->lower()->endsWith(CountryHelper::AVAILABLE_COUNTRIES);
        if ($isItRegional) {
            if (Str::length($thirdPart) > 2)  {
                $thirdPart = Str::substr($thirdPart, 0, -2);
            } else {
                $thirdPart = '';
            }

            return self::PROCESSOR_NAMESPACE . "Regionals\\$entity$thirdPart\\{$event}EventProcessor";
        }

        if ($thirdPart === 'Entity') {
            $thirdPart = '';
        }

        return self::PROCESSOR_NAMESPACE . "$entity$thirdPart\\{$event}EventProcessor";
    }
}

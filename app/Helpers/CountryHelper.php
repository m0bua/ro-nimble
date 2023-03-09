<?php
/**
 * Class CountryHelper
 * @package App\Helpers
 */

namespace App\Helpers;

class CountryHelper
{
    public const COUNTRY_UA = 'ua';
    public const COUNTRY_UZ = 'uz';
    public const COUNTRY_PL = 'pl';

    public const AVAILABLE_COUNTRIES = [
        self::COUNTRY_UA,
        self::COUNTRY_UZ,
        self::COUNTRY_PL,
    ];

    /**
     * Возвращает соответсвие стран и локалей
     * @return array
     */
    public static function getLocalesMap(): array
    {
        return [
            self::COUNTRY_UA => 'uk-UA',
            self::COUNTRY_UZ => 'uz-Latn-UZ',
        ];
    }

    /**
     * Возвращает соответствие стран и доменов
     * @return array
     */
    public static function getDomainsMap(): array
    {
        return [
            self::COUNTRY_UA => 'rozetka.com.ua',
            self::COUNTRY_UZ => 'rozetka.uz',
        ];
    }

    /**
     * Возвращает дефолтную страну
     * @return string
     */
    public static function getDefaultCountry(): string
    {
        return config('translatable.default_country');
    }

    /**
     * Возвращает страну из _GET запроса
     * @return string
     */
    public static function getRequestCountry(): string
    {
        return \strtolower((string) request('country', [self::getDefaultCountry()])[0]);
    }

    /**
     * Проверяет наличие страны среди доступных
     * @param string $country
     * @return bool
     */
    public static function hasCountry(string $country): bool
    {
        return array_key_exists($country, self::getLocalesMap());
    }

    /**
     * Возвращает параметр страны
     * @return string
     */
    public static function getCurrentCountry()
    {
        $urlCountry = self::getRequestCountry();

        return self::hasCountry($urlCountry) ? $urlCountry : self::getDefaultCountry();
    }

    /**
     * @return bool
     */
    public static function isUaCountry(): bool
    {
        return self::getCurrentCountry() == self::COUNTRY_UA;
    }
}

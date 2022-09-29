<?php
/**
 * Class LangHelper
 * @package App\Helpers
 */

namespace App\Helpers;

class LangHelper
{
    /**
     * Возвращает соответсвие стран и локалей
     * @return array
     */
    public static function getLocalesMap(): array
    {
        return config('translatable.locales_lang_map');
    }

    /**
     * Возвращает откорректированный язык
     * @param string $lang
     * @return string
     */
    public static function getCorrectLang(string $lang): string
    {
        return config('translatable.lang_corrector_map')[$lang] ?? $lang;
    }

    /**
     * Возвращает дефолтную страну
     * @return string
     */
    public static function getDefaultLang(): string
    {
        return config('translatable.default_language');
    }

    /**
     * Возвращает язык из _GET запроса
     * @return string
     */
    public static function getRequestLang(): string
    {
        return \strtolower((string) request('lang', [self::getDefaultLang()])[0]);
    }

    /**
     * Проверяет наличие локали среди доступных
     * @param string $lang
     * @param string $country
     * @return bool
     */
    public static function hasLocale(string $lang, string $country): bool
    {
        return \array_key_exists("{$lang}_{$country}", self::getLocalesMap());
    }

    /**
     * Возвращает параметр страны
     * @return string
     */
    public static function getCurrentLang()
    {
        $urlCountry = \strtoupper(CountryHelper::getRequestCountry());
        $urlLang = self::getCorrectLang(self::getRequestLang());

        return self::hasLocale($urlLang, $urlCountry) ? $urlLang : self::getDefaultLang();
    }
}

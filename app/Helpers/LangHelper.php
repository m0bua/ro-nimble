<?php
/**
 * Class LangHelper
 * @package App\Helpers
 */

namespace App\Helpers;

class LangHelper
{
    public const LOCALE_UA = 'ua';
    public const LOCALE_RU = 'ru';

    /**
     * Возвращает соответсвие стран и локалей
     * @return array
     */
    public static function getLocalesMap(): array
    {
        return [
            self::LOCALE_UA => 'uk-UA',
            self::LOCALE_RU => 'ru-RU',
        ];
    }

    /**
     * Возвращает соответсвие локалей и переводов
     * @return array
     */
    public static function getLocalesLangsList(): array
    {
        return [
            self::LOCALE_UA => 'uk',
            self::LOCALE_RU => 'ru',
        ];
    }

    /**
     * Возвращает язык для локали
     * @param $locale
     * @return string
     */
    public static function getLocaleLang($locale): string
    {
        return self::getLocalesLangsList()[$locale];
    }

    /**
     * Возвращает дефолтную страну
     * @return string
     */
    public static function getDefaultLang(): string
    {
        return self::LOCALE_RU;
    }

    /**
     * Возвращает язык из _GET запроса
     * @return string
     */
    public static function getRequestLang(): string
    {
        return strtolower((string) request('lang'));
    }

    /**
     * Проверяет наличие локали среди доступных
     * @param string $lang
     * @return bool
     */
    public static function hasLang(string $lang): bool
    {
        return array_key_exists($lang, self::getLocalesMap());
    }

    /**
     * Возвращает параметр страны
     * @return string
     */
    public static function getCurrentLang()
    {
        $urlCountry = self::getRequestLang();
        $currentLang = self::hasLang($urlCountry) ? $urlCountry : self::getDefaultLang();

        if ($currentLang == self::LOCALE_UA && !CountryHelper::isUaCountry()) {
            $currentLang = self::getDefaultLang();
        }

        return self::getLocaleLang($currentLang);
    }
}

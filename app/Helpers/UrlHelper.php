<?php
/**
 * Class UrlHelper
 * @package App\Helpers
 */

namespace App\Helpers;

class UrlHelper
{
    /**
     * @param string $url
     * @return string
     */
    public static function changeDomain(string $url): string
    {
        $currCountry = CountryHelper::getCurrentCountry();
        $domainsMap = CountryHelper::getDomainsMap();

        return str_replace(array_values($domainsMap), $domainsMap[$currCountry], $url);
    }
}

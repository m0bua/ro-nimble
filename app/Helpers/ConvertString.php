<?php
namespace App\Helpers;

class ConvertString
{
    public const PATTERN = '/([a-z])([A-Z])/';

    /**
     * @param string $string
     * @return string
     */
    public static function camelCaseToSnake($string = '')
    {
        return strtolower(
            preg_replace_callback(self::PATTERN, function ($a) {
                return $a[1] . "_" . strtolower ( $a[2] );
            }, $string)
        );
    }

    /**
     * @param $string
     * @param $prefix
     * @return string
     */
    public static function stringWithoutPrefix($string, $prefix) {
        return strtolower($string[strlen($prefix)]) . substr($string, strlen($prefix) + 1);
    }

    /**
     * @param $string
     * @param $prefixesList
     * @return mixed|null
     */
    public static function getPrefix($string, $prefixesList) {
        foreach ($prefixesList as $prefix) {
            if (strpos($string, $prefix) === 0) {
                return $prefix;
            }
        }

        return null;
    }

    /**
     * @param $propertyName
     * @return string
     */
    public static function getSetter($propertyName)
    {
        return sprintf('set%s', ucfirst($propertyName));
    }

    /**
     * @param array $data
     * @return string
     */
    public static function formattedOptions(array $data)
    {
        return $data ? sprintf(',%s,', implode(',', array_keys($data))) : '';
    }
}

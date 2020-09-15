<?php


namespace App\Helpers;


class ArrayHelper
{
    public static function merge(array $a, array $b): array
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            foreach (array_shift($args) as $k => $v) {
                if (is_int($k)) {
                    if (array_key_exists($k, $res)) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = array_unique(static::merge($res[$k], $v), SORT_REGULAR);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }
}

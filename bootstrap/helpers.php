<?php

if (!function_exists('is_assoc')) {
    /**
     * Check if array is associative
     * @param $var
     * @return bool
     */
    function is_assoc($var): bool
    {
        return is_array($var) && array_diff_key($var,array_keys(array_keys($var)));
    }
}

<?php

if (!function_exists('is_assoc')) {
    /**
     * Check if array is associative
     * @param $var
     * @return bool
     */
    function is_assoc($var): bool
    {
        return is_array($var) && array_diff_key($var, array_keys(array_keys($var)));
    }
}

if (!function_exists('encodeTranslationWithCompoundKey')) {
    /**
     * json_encode compound key for entity with its translation value
     *
     * @param array $key
     * @param string $value
     * @return string
     * @throws JsonException
     */
    function encodeTranslationWithCompoundKey(array $key, string $value): string
    {
        $filtered = array_filter($key, static fn($item) => $item !== null && $item > 0);
        if (count($filtered) !== count($key)) {
            /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
            throw new \LogicException('Invalid compound key');
        }

        return json_encode([
            'compoundKey' => $key,
            'value' => $value,
        ], JSON_THROW_ON_ERROR);
    }
}

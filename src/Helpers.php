<?php

if (!function_exists('data_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param mixed $array
     * @param string|null $key
     * @param mixed $default
     * @param string $glue
     * @return mixed
     */
    function data_get($array, ?string $key, $default = null, string $glue = '.')
    {
        if (!is_array($array)) {
            return $default;
        }

        if (is_null($key)) {
            return $array;
        }

        if (strlen($glue) == 0 || strpos($key, $glue) === false) {
            return $array[$key] ?? $default;
        }
        foreach (explode($glue, $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }
        return $array;
    }
}

if (!function_exists('millisecond_timestamp')) {
    /**
     * 获取毫秒级时间戳
     *
     * @return int
     */
    function millisecond_timestamp(): int
    {
        return intval(microtime(true) * 1000);
    }
}

if (!function_exists('env')) {
    /**
     * @param string|null $key
     * @param string|array|false|null $def
     * @return string|array|false|null
     */
    function env(?string $key, $def = null)
    {
        $value = getenv($key);
        return $value ?: $def;
    }
}
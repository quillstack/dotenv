<?php

declare(strict_types=1);

if (!function_exists('env')) {
    /**
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    function env(string $key, $default = '')
    {
        if (!isset($_ENV[$key])) {
            return $default;
        }

        return $_ENV[$key];
    }
}

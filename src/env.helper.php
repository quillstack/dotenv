<?php

declare(strict_types=1);

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;

if (!function_exists('env')) {
    /**
     * The value of an environment variable, or the default when it is not set.
     *
     * The default used to be null while the return type refused it, so asking for a
     * variable which was not set ended in a TypeError instead of giving back the default.
     */
    function env(string $key, string|int|float|bool|null $default = null): string|int|float|bool|null
    {
        $value = $_ENV[$key] ?? $default;

        return is_scalar($value) ? $value : $default;
    }
}

if (!function_exists('required')) {
    function required(string $key): string|int|float|bool
    {
        if (!isset($_ENV[$key]) || !is_scalar($_ENV[$key])) {
            throw new DotenvValueNotSetException("Value not set for key: {$key}");
        }

        return $_ENV[$key];
    }
}

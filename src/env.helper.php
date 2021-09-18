<?php

declare(strict_types=1);

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;

if (!function_exists('env')) {
    function env(string $key, string|int|float|bool $default = null): string|int|float|bool
    {
        if (!isset($_ENV[$key])) {
            return $default;
        }

        return $_ENV[$key];
    }
}

if (!function_exists('required')) {
    function required(string $key): string|int|float|bool
    {
        if (!isset($_ENV[$key])) {
            throw new DotenvValueNotSetException("Value not set for key: {$key}");
        }

        return $_ENV[$key];
    }
}

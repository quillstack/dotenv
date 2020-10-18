<?php

declare(strict_types=1);

namespace QuillStack\Dotenv;

use QuillStack\Dotenv\Exceptions\DotenvFileNotExistsException;
use QuillStack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;
use QuillStack\Dotenv\Exceptions\DotenvValueNotSetException;

final class Dotenv
{
    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        if (!is_file($path)) {
            throw new DotenvFileNotExistsException("File not found: {$path}");
        }

        $content = file_get_contents($path);
        $env = explode("\n", $content);

        foreach ($env as $index => $line) {
            $currentLine = $index + 1;
            $option = explode('=', $line);

            if ($option[0] === '') {
                continue;
            }

            if (!isset($option[1])) {
                throw new DotenvValueNotSetException("Value not set in line: {$currentLine}");
            }

            if (substr($option[0], 0, 5) === 'HTTP_') {
                throw new DotenvHttpPrefixNotAllowedException("HTTP_ prefix not allowed in line: {$currentLine}");
            }

            $_ENV["{$option[0]}"] = $option[1];
            $_SERVER["{$option[0]}"] = $option[1];
        }
    }
}

<?php

declare(strict_types=1);

namespace QuillStack\Dotenv;

use QuillStack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;
use QuillStack\Dotenv\Exceptions\DotenvValueNotSetException;
use QuillStack\Storage\StorageType\LocalStorage;

final class Dotenv
{
    /**
     * @var LocalStorage
     */
    public LocalStorage $storage;

    /**
     * @var string
     */
    private string $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Loads .env file.
     */
    public function load(): void
    {
        $content = $this->storage->get($this->path);
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

            // To protect against unexpected changes of HTTP headers.
            if (substr($option[0], 0, 5) === 'HTTP_') {
                throw new DotenvHttpPrefixNotAllowedException("HTTP_ prefix not allowed in line: {$currentLine}");
            }

            $value = $option[1];

            if (strcasecmp($value, 'true') === 0) {
                $value = true;
            } elseif (strcasecmp($value, 'false') === 0) {
                $value = false;
            }

            $_ENV["{$option[0]}"] = $value;
            $_SERVER["{$option[0]}"] = $value;
        }
    }
}

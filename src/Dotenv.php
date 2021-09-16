<?php

declare(strict_types=1);

namespace Quillstack\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;
use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;
use Quillstack\LocalStorage\LocalStorage;

class Dotenv
{
    public LocalStorage $storage;

    public function __construct(private string $path = '')
    {
        $this->storage = new LocalStorage();
    }

    /**
     * Loads .env file.
     */
    public function load(): void
    {
        if (empty($this->path)) {
            return;
        }

        $content = $this->storage->get($this->path);
        $env = explode("\n", $content);

        foreach ($env as $index => $line) {
            $currentLine = $index + 1;
            $option = explode('=', $line);

            if ($option[0] === '') {
                continue;
            }

            $this->validateKey($option, $currentLine);
            $value = $this->extractBooleanValues($option[1]);
            $this->saveToGlobals($option[0], $value);
        }
    }

    /**
     * Simple validation.
     */
    private function validateKey(array $option, int $currentLine): void
    {
        if (!isset($option[1])) {
            throw new DotenvValueNotSetException("Value not set in line: {$currentLine}");
        }

        // To protect against unexpected changes of HTTP headers.
        if (str_starts_with($option[0], 'HTTP_')) {
            throw new DotenvHttpPrefixNotAllowedException("HTTP_ prefix not allowed in line: {$currentLine}");
        }
    }

    /**
     * Tries to extract boolean values.
     */
    private function extractBooleanValues(mixed $value): mixed
    {
        if (strcasecmp($value, 'true') === 0) {
            return true;
        } elseif (strcasecmp($value, 'false') === 0) {
            return false;
        }

        return $value;
    }

    /**
     * Save values to global arrays.
     */
    private function saveToGlobals(string $key, mixed $value): void
    {
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

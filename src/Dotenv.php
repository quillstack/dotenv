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

            if (!$this->extractStringValue($option[1])) {
                $this->extractBooleanValues($option[1]);
                $this->extractNumericValues($option[1]);
            }

            $this->saveToGlobals($option[0], $option[1]);
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

    private function extractStringValue(mixed &$value): bool
    {
        if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
            $value = trim($value, '"');

            return true;
        }

        if (str_starts_with($value, "'") && str_ends_with($value, "'")) {
            $value = trim($value, "'");

            return true;
        }

        return false;
    }

    /**
     * Tries to extract boolean values.
     */
    private function extractBooleanValues(mixed &$value): void
    {
        if (strcasecmp($value, 'true') === 0) {
            $value = true;
        } elseif (strcasecmp($value, 'false') === 0) {
            $value = false;
        }
    }

    private function extractNumericValues(mixed &$value): void
    {
        if (!is_numeric($value)) {
            return;
        }

        if (strstr($value, '.')) {
            $value = (float) $value;
        } else {
            $value = (int) $value;
        }
    }

    /**
     * Save values to global arrays.
     */
    private function saveToGlobals(string $key, mixed $value): void
    {
        putenv(sprintf('%s=%s', $key, $value));
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

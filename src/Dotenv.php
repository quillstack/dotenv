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
            $lineArray = explode('=', $line);
            list ($key, $value) = $this->getKeyAndValue($lineArray);

            if ($key === '' || str_starts_with(trim($key), '#')) {
                continue;
            }

            $this->validateKey($lineArray, $currentLine);
            $this->extractValueTypes($value);
            $this->saveToGlobals($key, $value);
        }
    }

    private function getKeyAndValue(array $lineArray): array
    {
        return [
            array_shift($lineArray),
            implode('=', $lineArray),
        ];
    }

    private function extractValueTypes(mixed &$value): void
    {
        if ($this->extractStringValue($value)) {
            return;
        }

        if ($this->extractBooleanValues($value)) {
            return;
        }

        $this->extractNumericValues($value);
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
    private function extractBooleanValues(mixed &$value): bool
    {
        if (strcasecmp($value, 'true') === 0) {
            $value = true;

            return true;
        } elseif (strcasecmp($value, 'false') === 0) {
            $value = false;

            return true;
        }

        return false;
    }

    private function extractNumericValues(mixed &$value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        if (strstr($value, '.')) {
            $value = (float) $value;
        } else {
            $value = (int) $value;
        }

        return true;
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

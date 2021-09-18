<?php

declare(strict_types=1);

namespace Quillstack\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;
use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;
use Quillstack\LocalStorage\LocalStorage;

class Dotenv
{
    private LocalStorage $storage;
    private ValueTypes $valueTypes;

    public function __construct(private string $path = '')
    {
        $this->storage = new LocalStorage();
        $this->valueTypes = new ValueTypes();
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
            $lineArray = explode('=', $line);
            list($key, $value) = $this->getKeyAndValue($lineArray);

            if ($this->shouldBeSkipped($key)) {
                continue;
            }

            $this->validateKey($lineArray, $index);
            $this->valueTypes->extractValueTypes($value);
            $this->saveToGlobals($key, $value);
        }
    }

    private function shouldBeSkipped(string $key): bool
    {
        return $key === '' || str_starts_with(trim($key), '#');
    }

    private function getKeyAndValue(array $lineArray): array
    {
        return [
            array_shift($lineArray),
            implode('=', $lineArray),
        ];
    }

    private function validateKey(array $option, int $index): void
    {
        ++$index;

        if (!isset($option[1])) {
            throw new DotenvValueNotSetException("Value not set in line: {$index}");
        }

        // To protect against unexpected changes of HTTP headers.
        if (str_starts_with($option[0], 'HTTP_')) {
            throw new DotenvHttpPrefixNotAllowedException("HTTP_ prefix not allowed in line: {$index}");
        }
    }

    private function saveToGlobals(string $key, mixed $value): void
    {
        putenv(sprintf('%s=%s', $key, $value));
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

<?php

declare(strict_types=1);

namespace Quillstack\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;
use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;
use Quillstack\LocalStorage\LocalStorage;

class Dotenv
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
    public function __construct(string $path = '')
    {
        $this->path = $path;
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

            if (!isset($option[1])) {
                throw new DotenvValueNotSetException("Value not set in line: {$currentLine}");
            }

            // To protect against unexpected changes of HTTP headers.
            if (str_starts_with($option[0], 'HTTP_')) {
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

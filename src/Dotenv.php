<?php

declare(strict_types=1);

namespace Quillstack\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;
use Quillstack\Dotenv\Exceptions\DotenvInterpolationNotSupportedException;
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
     * Reads the file and puts what it holds into the environment.
     */
    public function load(): void
    {
        foreach ($this->parse() as $key => $value) {
            // Nothing here expands `${SOMETHING}`, and a value carrying one is not the value
            // the file meant — `URL=${BASE}/v1` would reach the application as a string that
            // looks like an address and is not one. Refusing is the only answer that does not
            // involve guessing.
            if (is_string($value) && Interpolation::isUsed($value)) {
                throw new DotenvInterpolationNotSupportedException(
                    "The value of `{$key}` uses `\${...}`, which this package does not expand. "
                    . 'Install quillstack/dotenv-expand to resolve it, or write `\\${` for a '
                    . 'literal `${`.'
                );
            }

            $this->saveToGlobals($key, is_string($value) ? Interpolation::unescape($value) : $value);
        }
    }

    /**
     * What the file holds, without touching the environment.
     *
     * References to other values are left exactly as they were written, escapes included, which
     * is what lets `quillstack/dotenv-expand` resolve them: it has to be able to tell `${BASE}`
     * from `\${BASE}`, and reading them would take that difference away.
     *
     * @return array<string, mixed>
     */
    public function parse(): array
    {
        if (empty($this->path)) {
            return [];
        }

        $content = $this->storage->get($this->path);
        $content = is_string($content) ? $content : '';
        $env = explode("\n", $content);
        $parsed = [];

        foreach ($env as $index => $line) {
            $lineArray = explode('=', $line);
            list($key, $value) = $this->getKeyAndValue($lineArray);

            if ($this->shouldBeSkipped($key)) {
                continue;
            }

            $this->validateKey($lineArray, $index);

            $key = $this->withoutExport($key);
            $value = $this->withoutComment($value);

            $this->valueTypes->extractValueTypes($value);
            $parsed[$key] = $value;
        }

        return $parsed;
    }

    /**
     * A key written the way it would be written for a shell.
     *
     * `export DB_PORT=5432` is how the same file is read by `source`, and it is common enough
     * in a `.env` that not understanding it does not mean the line is skipped: the key became
     * `export DB_PORT`, and `DB_PORT` was simply never set.
     */
    private function withoutExport(string $key): string
    {
        $trimmed = ltrim($key);

        if (!str_starts_with($trimmed, 'export') || !preg_match('/^export\s+(\S.*)$/', $trimmed, $parts)) {
            return $key;
        }

        return $parts[1];
    }

    /**
     * The value, with anything a reader would call a comment taken off the end.
     *
     * `DB_PORT=5432 # the default` used to be read as the whole of `5432 # the default`: no
     * error, nothing empty, just a value that looks right and is not. A `#` starts a comment
     * only where a shell would treat it as one — after whitespace, and outside quotes — so
     * `KEY=foo#bar` is still `foo#bar`, and a `#` inside a quoted value stays put.
     */
    private function withoutComment(string $value): string
    {
        $from = 0;
        $trimmed = ltrim($value);
        $quote = $trimmed === '' ? '' : $trimmed[0];

        if ($quote === '"' || $quote === "'") {
            $opening = strpos($value, $quote);
            $closing = $opening === false ? false : strpos($value, $quote, $opening + 1);

            if ($closing === false) {
                return $value;
            }

            $from = $closing + 1;
        }

        $mark = $this->commentPosition($value, $from);

        return $mark === null ? $value : rtrim(substr($value, 0, $mark));
    }

    /**
     * Where the comment starts, looking from `$from` onwards, or null where there is none.
     */
    private function commentPosition(string $value, int $from): ?int
    {
        $length = strlen($value);

        for ($i = $from; $i < $length; ++$i) {
            if ($value[$i] !== '#') {
                continue;
            }

            // At the very start of the value, or after a space or a tab. `hunter2#` is a
            // password, not a comment nobody wrote.
            if ($i === 0 || $value[$i - 1] === ' ' || $value[$i - 1] === "\t") {
                return $i;
            }
        }

        return null;
    }

    private function shouldBeSkipped(string $key): bool
    {
        return $key === '' || str_starts_with(trim($key), '#');
    }

    /**
     * Splits a line into its key and its value, keeping any equals signs inside the value.
     *
     * @param string[] $lineArray
     *
     * @return array{0: string, 1: string}
     */
    private function getKeyAndValue(array $lineArray): array
    {
        $key = array_shift($lineArray) ?? '';

        return [$key, implode('=', $lineArray)];
    }

    /**
     * @param string[] $option
     */
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
        putenv($key . '=' . (is_scalar($value) ? (string) $value : ''));
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

<?php

declare(strict_types=1);

namespace Quillstack\Dotenv;

/**
 * Where a value refers to another one, and how to write a `${` that means only itself.
 *
 * This package does not expand those references — `quillstack/dotenv-expand` does. What lives
 * here is the part both have to agree on, because one of them refuses what it finds and the
 * other resolves it, and they must find exactly the same things.
 */
final class Interpolation
{
    /**
     * A `${NAME}` which was not escaped. The name is left loose on purpose: an expression this
     * package will not expand is worth refusing whether or not the name inside it is one that
     * could ever have resolved.
     *
     * @var string
     */
    public const PATTERN = '/(?<!\\\\)\$\{([^}]*)\}/';

    /**
     * Tells whether the value refers to something that has to be expanded before it can be
     * used. `\${HOME}` does not: it is how a `${` is written when it means itself.
     */
    public static function isUsed(string $value): bool
    {
        return preg_match(self::PATTERN, $value) === 1;
    }

    /**
     * Every name the value refers to, in the order it refers to them.
     *
     * @return string[]
     */
    public static function names(string $value): array
    {
        preg_match_all(self::PATTERN, $value, $matches);

        return $matches[1];
    }

    /**
     * The value as it is meant to be read, once nothing is left to expand: `\${` was a way of
     * writing `${`, and having served that purpose it stops being an escape.
     */
    public static function unescape(string $value): string
    {
        return str_replace('\\${', '${', $value);
    }
}

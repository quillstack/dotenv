<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Exceptions;

use Quillstack\Dotenv\DotenvException;

/**
 * Thrown where a value refers to another one and nothing here can resolve it.
 *
 * Left alone it would reach the application as the literal text somebody wrote — no error, and
 * a value that looks right and is not. That is the failure this whole package is careful about.
 */
class DotenvInterpolationNotSupportedException extends DotenvException
{
}

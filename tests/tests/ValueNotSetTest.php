<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;

final class ValueNotSetTest extends AbstractEnvironment
{
    public function testNotExistingFile()
    {
        $this->expectException(DotenvValueNotSetException::class);

        $path = dirname(__FILE__) . '/../fixtures/value-not-set.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }
}

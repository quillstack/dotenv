<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;

final class ValueNotSetTest extends AbstractEnvironment
{
    public function testNotExistingFile()
    {
        $this->expectException(DotenvValueNotSetException::class);
        $this->expectExceptionMessage('Value not set in line: 3');

        $path = dirname(__FILE__).'/../fixtures/value-not-set.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }
}

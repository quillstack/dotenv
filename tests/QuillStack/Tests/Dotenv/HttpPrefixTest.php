<?php

declare(strict_types=1);

namespace QuillStack\Tests\Dotenv;

use QuillStack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;
use QuillStack\Tests\AbstractEnvironmentTest;

final class HttpPrefixTest extends AbstractEnvironmentTest
{
    public function testNotExistingFile()
    {
        $this->expectException(DotenvHttpPrefixNotAllowedException::class);

        $path = dirname(__FILE__) . '/../Mocks/Fixtures/http-prefix.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }
}

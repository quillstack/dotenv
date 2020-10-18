<?php

declare(strict_types=1);

namespace QuillStack\Tests\Dotenv;

use QuillStack\Dotenv\Dotenv;
use QuillStack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;
use QuillStack\Tests\AbstractEnvironmentTest;

final class HttpPrefixTest extends AbstractEnvironmentTest
{
    public function testNotExistingFile()
    {
        $this->expectException(DotenvHttpPrefixNotAllowedException::class);

        $simple = dirname(__FILE__) . "/../Mocks/Fixtures/http-prefix.env";
        new Dotenv($simple);
    }
}

<?php

declare(strict_types=1);

namespace QuillStack\Tests\Dotenv;

use QuillStack\Dotenv\Dotenv;
use QuillStack\Dotenv\Exceptions\DotenvFileNotExistsException;
use QuillStack\Tests\AbstractEnvironmentTest;

final class SimpleFileTest extends AbstractEnvironmentTest
{
    public function testSimpleFile()
    {
        $simple = dirname(__FILE__) . "/../Mocks/Fixtures/simple.env";
        new Dotenv($simple);

        $this->assertEquals('localhost', env('DATABASE_HOST'));
        $this->assertEquals('localhost', env('DATABASE_HOST'));
        $this->assertEquals('default', env('DATABASE_SECONDARY', 'default'));
    }

    public function testNotExistingFile()
    {
        $this->expectException(DotenvFileNotExistsException::class);

        $simple = dirname(__FILE__) . "/../Mocks/Fixtures/not-found.env";
        new Dotenv($simple);
    }
}

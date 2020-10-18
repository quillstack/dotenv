<?php

declare(strict_types=1);

namespace QuillStack\Tests\Dotenv;

use QuillStack\Storage\Exceptions\FileNotExistsException;
use QuillStack\Tests\AbstractEnvironmentTest;

final class SimpleFileTest extends AbstractEnvironmentTest
{
    public function testSimpleFile()
    {
        $path = dirname(__FILE__) . '/../Mocks/Fixtures/simple.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();

        $this->assertEquals('localhost', env('DATABASE_HOST'));
        $this->assertEquals('localhost', env('DATABASE_HOST'));
        $this->assertEquals('default', env('DATABASE_SECONDARY', 'default'));
    }

    public function testNotExistingFile()
    {
        $this->expectException(FileNotExistsException::class);

        $path = dirname(__FILE__) . '/../Mocks/Fixtures/not-found.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }
}

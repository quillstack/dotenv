<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

use Quillstack\LocalStorage\Exceptions\LocalFileNotExistsException;

final class SimpleFileTest extends AbstractEnvironment
{
    protected function setUp(): void
    {
        parent::setUp();

        $path = dirname(__FILE__) . '/../fixtures/simple.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testSimpleFile()
    {
        $this->assertEquals('localhost', env('DATABASE_HOST'));
        $this->assertEquals('localhost', env('DATABASE_HOST'));
        $this->assertEquals('default', env('DATABASE_SECONDARY', 'default'));
    }

    public function testNotExistingFile()
    {
        $this->expectException(LocalFileNotExistsException::class);

        $path = dirname(__FILE__) . '/../fixtures/not-found.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }
}

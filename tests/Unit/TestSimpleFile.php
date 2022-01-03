<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\LocalStorage\Exceptions\LocalFileNotExistsException;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;

class TestSimpleFile extends AbstractEnvironment
{
    public function __construct(private AssertEqual $assertEqual, private AssertExceptions $assertExceptions)
    {
        parent::__construct();

        $path = dirname(__FILE__) . '/../Fixtures/simple.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function simpleFile()
    {
        $this->assertEqual->equal('localhost', env('DATABASE_HOST'));
        $this->assertEqual->equal('default', env('DATABASE_SECONDARY', 'default'));
    }

    public function notExistingFile()
    {
        $this->assertExceptions->expect(LocalFileNotExistsException::class);

        $path = dirname(__FILE__) . '/../Fixtures/not-found.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }
}

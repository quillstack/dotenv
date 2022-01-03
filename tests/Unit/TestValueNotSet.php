<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;
use Quillstack\UnitTests\AssertExceptions;

class TestValueNotSet extends AbstractEnvironment
{
    public function __construct(private AssertExceptions $assertExceptions)
    {
        parent::__construct();
    }

    public function testNotExistingFile()
    {
        $this->assertExceptions->expect(DotenvValueNotSetException::class);
        $this->assertExceptions->expectMessage('Value not set in line: 3');

        $path = dirname(__FILE__) . '/../Fixtures/value-not-set.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }
}

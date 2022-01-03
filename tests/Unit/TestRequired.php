<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;

class TestRequired extends AbstractEnvironment
{
    public function __construct(private AssertEqual $assertEqual, private AssertExceptions $assertExceptions)
    {
        parent::__construct();

        $path = dirname(__FILE__) . '/../Fixtures/simple.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testRequiredHelperFunction()
    {
        $this->assertEqual->equal('localhost', required('DATABASE_HOST'));
    }

    public function testException()
    {
        $this->assertExceptions->expect(DotenvValueNotSetException::class);
        $this->assertExceptions->expectMessage('Value not set for key: DATABASE_SECONDARY');

        required('DATABASE_SECONDARY');
    }
}

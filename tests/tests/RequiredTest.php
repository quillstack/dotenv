<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;

class RequiredTest extends AbstractEnvironment
{
    protected function setUp(): void
    {
        parent::setUp();

        $path = dirname(__FILE__) . '/../fixtures/simple.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testRequiredHelperFunction()
    {
        $this->assertEquals('localhost', required('DATABASE_HOST'));
        $this->assertEquals('localhost', required('DATABASE_HOST'));
    }

    public function testException()
    {
        $this->expectException(DotenvValueNotSetException::class);
        $this->expectExceptionMessage('Value not set for key: DATABASE_SECONDARY');

        $this->assertEquals('default', required('DATABASE_SECONDARY'));
    }
}

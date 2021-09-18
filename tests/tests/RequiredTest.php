<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;

class RequiredTest extends AbstractEnvironment
{
    public function testRequiredHelperFunction()
    {
        $path = dirname(__FILE__) . '/../fixtures/simple.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();

        $this->assertEquals('localhost', required('DATABASE_HOST'));
        $this->assertEquals('localhost', required('DATABASE_HOST'));
    }

    public function testException()
    {
        $this->expectException(DotenvValueNotSetException::class);

        $path = dirname(__FILE__) . '/../fixtures/simple.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();

        $this->assertEquals('default', required('DATABASE_SECONDARY'));
    }
}

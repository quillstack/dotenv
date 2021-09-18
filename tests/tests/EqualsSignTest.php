<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

class EqualsSignTest extends AbstractEnvironment
{
    protected function setUp(): void
    {
        parent::setUp();

        $path = dirname(__FILE__).'/../fixtures/equals-sign.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testEqualsSign()
    {
        $this->assertIsString(env('EQUALS_SIGN'));
        $this->assertEquals('some=random', env('EQUALS_SIGN'));
        $this->assertIsString(required('EQUALS_SIGN'));
        $this->assertEquals('some=random=string', required('EQUALS_SIGNS'));
        $this->assertIsString(required('EQUALS_SIGN_QUOTE'));
        $this->assertEquals('some=random', required('EQUALS_SIGN_QUOTE'));
    }
}

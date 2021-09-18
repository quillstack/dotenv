<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

final class BooleanTest extends AbstractEnvironment
{
    protected function setUp(): void
    {
        parent::setUp();

        $path = dirname(__FILE__) . '/../fixtures/bool.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testBooleanValue()
    {
        $this->assertTrue(env('APP_TRUE_LOWER'));
        $this->assertTrue(env('APP_TRUE_UPPER'));
        $this->assertFalse(env('APP_FALSE_LOWER'));
        $this->assertFalse(env('APP_FALSE_UPPER'));
    }
}

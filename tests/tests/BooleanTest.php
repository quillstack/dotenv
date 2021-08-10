<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

final class BooleanTest extends AbstractEnvironment
{
    public function testBooleanValue()
    {
        $path = dirname(__FILE__) . '/../fixtures/bool.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();

        $this->assertTrue(env('APP_TRUE_LOWER'));
        $this->assertTrue(env('APP_TRUE_UPPER'));
        $this->assertFalse(env('APP_FALSE_LOWER'));
        $this->assertFalse(env('APP_FALSE_UPPER'));
    }
}

<?php

declare(strict_types=1);

namespace QuillStack\Tests\Dotenv;

use QuillStack\Tests\AbstractEnvironmentTest;

final class BooleanTest extends AbstractEnvironmentTest
{
    public function testBooleanValue()
    {
        $path = dirname(__FILE__) . '/../Mocks/Fixtures/bool.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();

        $this->assertTrue(env('APP_TRUE_LOWER'));
        $this->assertTrue(env('APP_TRUE_UPPER'));
        $this->assertFalse(env('APP_FALSE_LOWER'));
        $this->assertFalse(env('APP_FALSE_UPPER'));
    }
}

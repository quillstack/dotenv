<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

class MultiLineTest extends AbstractEnvironment
{
    protected function setUp(): void
    {
        parent::setUp();

        $path = dirname(__FILE__).'/../fixtures/multi-line.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testEqualsSign()
    {
        $this->assertEquals("line1\nline2\nline3", env('PRIVATE_KEY'));
    }
}

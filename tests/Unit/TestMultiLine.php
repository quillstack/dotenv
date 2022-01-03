<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

class TestMultiLine extends AbstractEnvironment
{
    public function __construct()
    {
        parent::__construct();

        $path = dirname(__FILE__) . '/../Fixtures/multi-line.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    protected function setUp(): void
    {
        parent::setUp();

    }

    public function testEqualsSign()
    {
        $this->assertEquals("line1\nline2\nline3", env('PRIVATE_KEY'));
    }
}

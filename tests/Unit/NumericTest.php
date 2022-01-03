<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

class NumericTest extends AbstractEnvironment
{
    protected function setUp(): void
    {
        parent::setUp();

        $path = dirname(__FILE__) . '/../fixtures/numeric.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testFloatValue()
    {
        $this->assertIsNumeric(env('FLOAT'));
        $this->assertIsFloat(env('FLOAT'));
        $this->assertIsNumeric(env('INT'));
        $this->assertIsInt(env('INT'));
    }
}

<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

class NumericTest extends AbstractEnvironment
{
    public function testFloatValue()
    {
        $path = dirname(__FILE__) . '/../fixtures/numeric.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();

        $this->assertIsNumeric(env('FLOAT'));
        $this->assertIsFloat(env('FLOAT'));
        $this->assertIsNumeric(env('INT'));
        $this->assertIsInt(env('INT'));
    }
}

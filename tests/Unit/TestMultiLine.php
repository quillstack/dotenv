<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\UnitTests\AssertEqual;

class TestMultiLine extends AbstractEnvironment
{
    public function __construct(private AssertEqual $assertEqual)
    {
        parent::__construct();

        $path = dirname(__FILE__) . '/../Fixtures/multi-line.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testEqualsSign()
    {
        $this->assertEqual->equal("line1\nline2\nline3", env('PRIVATE_KEY'));
    }
}

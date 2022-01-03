<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\UnitTests\Types\AssertNull;

class TestEmptyPath extends AbstractEnvironment
{
    public function __construct(private AssertNull $assertNull)
    {
        parent::__construct();
    }

    public function testEmptyPath()
    {
        $dotenv = $this->getDotenvWithPath('');

        $this->assertNull->isNull($dotenv->load());
    }
}

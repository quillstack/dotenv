<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

class EmptyPathTest extends AbstractEnvironment
{
    public function testEmptyPath()
    {
        $dotenv = $this->getDotenvWithPath('');

        $this->assertNull($dotenv->load());
    }
}

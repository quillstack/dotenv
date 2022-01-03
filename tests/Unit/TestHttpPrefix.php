<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;
use Quillstack\UnitTests\AssertExceptions;

class TestHttpPrefix extends AbstractEnvironment
{
    public function __construct(private AssertExceptions $assertExceptions)
    {
        parent::__construct();
    }

    public function testNotExistingFile()
    {
        $this->assertExceptions->expect(DotenvHttpPrefixNotAllowedException::class);

        $path = dirname(__FILE__) . '/../fixtures/http-prefix.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }
}

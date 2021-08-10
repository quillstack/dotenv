<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvHttpPrefixNotAllowedException;

final class HttpPrefixTest extends AbstractEnvironment
{
    public function testNotExistingFile()
    {
        $this->expectException(DotenvHttpPrefixNotAllowedException::class);

        $path = dirname(__FILE__) . '/../fixtures/http-prefix.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }
}

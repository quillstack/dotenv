<?php

declare(strict_types=1);

namespace QuillStack\Tests\Dotenv;

use QuillStack\Dotenv\Dotenv;
use QuillStack\Dotenv\Exceptions\DotenvValueNotSetException;
use QuillStack\Tests\AbstractEnvironmentTest;

final class ValueNotSetTest extends AbstractEnvironmentTest
{
    public function testNotExistingFile()
    {
        $this->expectException(DotenvValueNotSetException::class);

        $simple = dirname(__FILE__) . "/../Mocks/Fixtures/value-not-set.env";
        new Dotenv($simple);
    }

}

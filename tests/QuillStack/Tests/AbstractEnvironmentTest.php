<?php

declare(strict_types=1);

namespace QuillStack\Tests;

use PHPUnit\Framework\TestCase;

abstract class AbstractEnvironmentTest extends TestCase
{
    private array $cache;

    protected function setUp(): void
    {
        $this->cache['env'] = $_ENV;
        $this->cache['server'] = $_SERVER;
    }

    protected function tearDown(): void
    {
        $_ENV = $this->cache['env'];
        $_SERVER = $this->cache['server'];
    }
}

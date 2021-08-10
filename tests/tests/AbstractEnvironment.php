<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

use PHPUnit\Framework\TestCase;
use Quillstack\Dotenv\Dotenv;

abstract class AbstractEnvironment extends TestCase
{
    /**
     * @var array
     */
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

    protected function getDotenvWithPath(string $path): Dotenv
    {
        return new Dotenv($path);
    }
}
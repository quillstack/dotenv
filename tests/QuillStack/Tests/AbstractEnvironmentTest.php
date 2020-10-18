<?php

declare(strict_types=1);

namespace QuillStack\Tests;

use PHPUnit\Framework\TestCase;
use QuillStack\DI\Container;
use QuillStack\Dotenv\Dotenv;

abstract class AbstractEnvironmentTest extends TestCase
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
        $container = new Container([
            Dotenv::class => [
                'path' => $path,
            ]
        ]);

        return $container->get(Dotenv::class);
    }
}

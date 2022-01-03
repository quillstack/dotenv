<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\Dotenv\Dotenv;

abstract class AbstractEnvironment
{
    /**
     * @var array
     */
    private array $cache;

    public function __construct()
    {
        $this->cache['env'] = $_ENV;
        $this->cache['server'] = $_SERVER;
    }

    public function __destruct()
    {
        $_ENV = $this->cache['env'];
        $_SERVER = $this->cache['server'];
    }

    protected function getDotenvWithPath(string $path): Dotenv
    {
        return new Dotenv($path);
    }
}

<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\UnitTests\Types\AssertBoolean;

class TestBoolean extends AbstractEnvironment
{
    public function __construct(private AssertBoolean $assertBoolean)
    {
        parent::__construct();

        $path = dirname(__FILE__) . '/../Fixtures/bool.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function booleanValue()
    {
        $this->assertBoolean->isTrue(env('APP_TRUE_LOWER'));
        $this->assertBoolean->isTrue(env('APP_TRUE_UPPER'));
        $this->assertBoolean->isFalse(env('APP_FALSE_LOWER'));
        $this->assertBoolean->isFalse(env('APP_FALSE_UPPER'));
    }
}

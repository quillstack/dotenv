<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\UnitTests\Types\AssertNumeric;

class TestNumeric extends AbstractEnvironment
{
    public function __construct(private AssertNumeric $assertNumeric)
    {
        parent::__construct();

        $path = dirname(__FILE__) . '/../Fixtures/numeric.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function values()
    {
        $this->assertNumeric->isNumeric(env('FLOAT'));
        $this->assertNumeric->isFloat(env('FLOAT'));

        $this->assertNumeric->isNumeric(env('INT'));
        $this->assertNumeric->isInt(env('INT'));
    }
}

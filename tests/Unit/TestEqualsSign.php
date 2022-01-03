<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\UnitTests\Types\AssertString;

class TestEqualsSign extends AbstractEnvironment
{
    public function __construct(private AssertString $assertString)
    {
        parent::__construct();

        $path = dirname(__FILE__) . '/../Fixtures/equals-sign.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function equalsSign()
    {
        $this->assertString->isString(env('EQUALS_SIGN'));
        $this->assertString->equal('some=random', env('EQUALS_SIGN'));

        $this->assertString->isString(required('EQUALS_SIGN'));
        $this->assertString->equal('some=random=string', required('EQUALS_SIGNS'));

        $this->assertString->isString(required('EQUALS_SIGN_QUOTE'));
        $this->assertString->equal('some=random', required('EQUALS_SIGN_QUOTE'));
    }
}

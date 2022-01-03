<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;

class TestComments extends AbstractEnvironment
{
    public function __construct(private AssertExceptions $assertExceptions, private AssertEqual $assertEqual)
    {
        parent::__construct();

        $path = dirname(__FILE__) . '/../fixtures/comments.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function comment()
    {
        $this->assertExceptions->expect(DotenvValueNotSetException::class);

        $this->assertEqual->equal('comments', required('DATABASE_NAME'));
        required('DATABASE_HOST');
    }

    public function commentWithSpace()
    {
        $this->assertExceptions->expect(DotenvValueNotSetException::class);

        $this->assertEqual->equal('comments', required('DATABASE_NAME'));
        required('DATABASE_PASSWORD');
    }

    public function commentWithTab()
    {
        $this->assertExceptions->expect(DotenvValueNotSetException::class);

        $this->assertEqual->equal('comments', required('DATABASE_NAME'));
        required('DATABASE_USER');
    }
}

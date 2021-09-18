<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

use Quillstack\Dotenv\Exceptions\DotenvValueNotSetException;

class CommentsTest extends AbstractEnvironment
{
    protected function setUp(): void
    {
        parent::setUp();

        $path = dirname(__FILE__) . '/../fixtures/comments.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testComment()
    {
        $this->expectException(DotenvValueNotSetException::class);

        $this->assertEquals('comments', required('DATABASE_NAME'));
        required('DATABASE_HOST');
    }

    public function testCommentWithSpace()
    {
        $this->expectException(DotenvValueNotSetException::class);

        $this->assertEquals('comments', required('DATABASE_NAME'));
        required('DATABASE_PASSWORD');
    }

    public function testCommentWithTab()
    {
        $this->expectException(DotenvValueNotSetException::class);

        $this->assertEquals('comments', required('DATABASE_NAME'));
        required('DATABASE_USER');
    }
}

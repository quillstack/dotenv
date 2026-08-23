<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\UnitTests\AssertEqual;

/**
 * A `#` after a value starts a comment, the way it does in the shell this file is shaped after.
 *
 * This used to be read as part of the value: `PORT=5432 # the default` gave the whole of
 * `5432 # the default` — no error, nothing empty, and an application holding a value that looks
 * right and is not.
 */
class TestTrailingComments extends AbstractEnvironment
{
    public function __construct(private AssertEqual $assertEqual)
    {
        parent::__construct();

        $this->getDotenvWithPath(dirname(__FILE__) . '/../Fixtures/trailing-comments.env')->load();
    }

    public function aCommentAfterAValueIsNotPartOfIt()
    {
        $this->assertEqual->equal('foo', env('SPACED'));
        $this->assertEqual->equal('foo', env('SPACED_WIDE'));
    }

    /**
     * Only after whitespace, because a `#` in the middle of a word is a `#` in the middle of a
     * word — and passwords are full of them.
     */
    public function aHashInsideAValueStays()
    {
        $this->assertEqual->equal('foo#bar', env('NO_SPACE'));
        $this->assertEqual->equal('hunter2#', env('TRAILING_HASH'));
    }

    public function aHashInsideQuotesStays()
    {
        $this->assertEqual->equal('quoted # hash', env('QUOTED_HASH'));
    }

    public function aCommentAfterTheClosingQuoteGoes()
    {
        $this->assertEqual->equal('quoted', env('QUOTED_THEN_COMMENT'));
    }

    /**
     * The type is read from what is left once the comment is off, so a commented number is
     * still a number.
     */
    public function whatIsLeftKeepsItsType()
    {
        $this->assertEqual->equal(5432, env('PORT'));
        $this->assertEqual->equal(true, env('DEBUG'));
    }
}

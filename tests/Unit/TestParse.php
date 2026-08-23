<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;

/**
 * Reading a file without changing anything: there was no way to see what a `.env` held short of
 * putting it into the environment and looking there.
 */
class TestParse extends AbstractEnvironment
{
    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        parent::__construct();
    }

    public function itHandsBackWhatTheFileHolds()
    {
        $parsed = $this->getDotenvWithPath(dirname(__FILE__) . '/../Fixtures/trailing-comments.env')->parse();

        $this->assertEqual->equal(5432, $parsed['PORT']);
        $this->assertEqual->equal('foo#bar', $parsed['NO_SPACE']);
    }

    public function itChangesNothing()
    {
        $this->getDotenvWithPath(dirname(__FILE__) . '/../Fixtures/export.env')->parse();

        $this->assertBoolean->isFalse(array_key_exists('EXPORTED', $_ENV));
        $this->assertBoolean->isFalse(getenv('EXPORTED') !== false);
    }

    public function nothingIsReadWithoutAPath()
    {
        $this->assertEqual->equal([], $this->getDotenvWithPath('')->parse());
    }
}

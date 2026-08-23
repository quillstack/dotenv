<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;

/**
 * `export KEY=value` is how the same file is read by `source`, and common enough in a `.env`
 * that not understanding it did not skip the line: the key became `export KEY`, and `KEY` was
 * simply never set.
 */
class TestExport extends AbstractEnvironment
{
    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        parent::__construct();

        $this->getDotenvWithPath(dirname(__FILE__) . '/../Fixtures/export.env')->load();
    }

    public function theKeywordIsNotPartOfTheName()
    {
        $this->assertEqual->equal('yes', env('EXPORTED'));
        $this->assertBoolean->isFalse(array_key_exists('export EXPORTED', $_ENV));
    }

    public function itWorksWithEverythingElseOnTheLine()
    {
        $this->assertEqual->equal('two words', env('QUOTED'));
    }

    public function aLineWithoutItIsUnchanged()
    {
        $this->assertEqual->equal('no', env('PLAIN'));
    }
}

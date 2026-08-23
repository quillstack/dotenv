<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\Dotenv\Exceptions\DotenvInterpolationNotSupportedException;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;

/**
 * Nothing here expands `${SOMETHING}`, and a value carrying one is refused rather than passed
 * on. `URL=${BASE}/v1` left alone reaches the application as a string that looks like an
 * address and is not one — which is worse than not starting.
 */
class TestInterpolation extends AbstractEnvironment
{
    public function __construct(
        private AssertEqual $assertEqual,
        private AssertExceptions $assertExceptions
    ) {
        parent::__construct();
    }

    public function aValueWhichHasToBeExpandedIsRefused()
    {
        $this->assertExceptions->expect(DotenvInterpolationNotSupportedException::class);

        $this->getDotenvWithPath(dirname(__FILE__) . '/../Fixtures/interpolation.env')->load();
    }

    /**
     * Reading the file is not the same as accepting it: `parse()` is what
     * `quillstack/dotenv-expand` builds on, so it hands back exactly what was written.
     */
    public function readingItIsStillAllowed()
    {
        $parsed = $this->getDotenvWithPath(dirname(__FILE__) . '/../Fixtures/interpolation.env')->parse();

        $this->assertEqual->equal('${BASE}/v1', $parsed['URL']);
        $this->assertEqual->equal('https://example.org', $parsed['BASE']);
    }

    /**
     * `\${` is how a `${` is written when it means only itself, and having said so it stops
     * being an escape.
     */
    public function anEscapedOneIsJustText()
    {
        $this->getDotenvWithPath(dirname(__FILE__) . '/../Fixtures/escaped-interpolation.env')->load();

        $this->assertEqual->equal('${9.99}', env('PRICE'));
        $this->assertEqual->equal('cost is ${AMOUNT} exactly', env('PATTERN'));
    }
}

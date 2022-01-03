<?php

declare(strict_types=1);

namespace Quillstack\Dotenv\Tests\Unit;

use Quillstack\UnitTests\Types\AssertString;

class TestQuotationMarks extends AbstractEnvironment
{
    public function __construct(private AssertString $assertString)
    {
        parent::__construct();

        $path = dirname(__FILE__) . '/../Fixtures/quotation-marks.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function stringValue()
    {
        $this->assertString->isString(env('QUOTATION_MARKS_STRING'));
        $this->assertString->isString(env('QUOTATION_MARKS_STRING_SINGLE'));
        $this->assertString->equal('quote_test', required('QUOTATION_MARKS_STRING'));
        $this->assertString->equal('quote_test', required('QUOTATION_MARKS_STRING_SINGLE'));
    }

    public function boolValuesSavedAsString()
    {
        $this->assertString->isString(env('QUOTATION_MARKS_TRUE'));
        $this->assertString->isString(env('QUOTATION_MARKS_TRUE_SINGLE'));
        $this->assertString->equal('true', required('QUOTATION_MARKS_TRUE'));
        $this->assertString->equal('true', required('QUOTATION_MARKS_TRUE_SINGLE'));

        $this->assertString->isString(env('QUOTATION_MARKS_FALSE'));
        $this->assertString->isString(env('QUOTATION_MARKS_FALSE_SINGLE'));
        $this->assertString->equal('false', required('QUOTATION_MARKS_FALSE'));
        $this->assertString->equal('false', required('QUOTATION_MARKS_FALSE_SINGLE'));
    }

    public function intValueSavedAsString()
    {
        $this->assertString->isString(env('QUOTATION_MARKS_INT'));
        $this->assertString->isString(env('QUOTATION_MARKS_INT_SINGLE'));
        $this->assertString->equal('8', required('QUOTATION_MARKS_INT'));
        $this->assertString->equal('8', required('QUOTATION_MARKS_INT_SINGLE'));
    }

    public function floatValueSavedAsString()
    {
        $this->assertString->isString(env('QUOTATION_MARKS_FLOAT'));
        $this->assertString->isString(env('QUOTATION_MARKS_FLOAT_SINGLE'));
        $this->assertString->equal('8.64', required('QUOTATION_MARKS_FLOAT'));
        $this->assertString->equal('8.64', required('QUOTATION_MARKS_FLOAT_SINGLE'));
    }
}

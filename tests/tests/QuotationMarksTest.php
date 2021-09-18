<?php

declare(strict_types=1);

namespace Quillstack\Tests\Dotenv;

class QuotationMarksTest extends AbstractEnvironment
{
    protected function setUp(): void
    {
        parent::setUp();

        $path = dirname(__FILE__) . '/../fixtures/quotation-marks.env';
        $dotenv = $this->getDotenvWithPath($path);
        $dotenv->load();
    }

    public function testStringValue()
    {
        $this->assertIsString(env('QUOTATION_MARKS_STRING'));
        $this->assertIsString(env('QUOTATION_MARKS_STRING_SINGLE'));
        $this->assertEquals('quote_test', required('QUOTATION_MARKS_STRING'));
        $this->assertEquals('quote_test', required('QUOTATION_MARKS_STRING_SINGLE'));
    }

    public function testBoolValue()
    {
        $this->assertIsString(env('QUOTATION_MARKS_TRUE'));
        $this->assertIsString(env('QUOTATION_MARKS_TRUE_SINGLE'));
        $this->assertEquals('true', required('QUOTATION_MARKS_TRUE'));
        $this->assertEquals('true', required('QUOTATION_MARKS_TRUE_SINGLE'));

        $this->assertIsString(env('QUOTATION_MARKS_FALSE'));
        $this->assertIsString(env('QUOTATION_MARKS_FALSE_SINGLE'));
        $this->assertEquals('false', required('QUOTATION_MARKS_FALSE'));
        $this->assertEquals('false', required('QUOTATION_MARKS_FALSE_SINGLE'));
    }

    public function testIntValue()
    {
        $this->assertIsString(env('QUOTATION_MARKS_INT'));
        $this->assertIsString(env('QUOTATION_MARKS_INT_SINGLE'));
        $this->assertEquals('8', required('QUOTATION_MARKS_INT'));
        $this->assertEquals('8', required('QUOTATION_MARKS_INT_SINGLE'));
    }

    public function testFloatValue()
    {
        $this->assertIsString(env('QUOTATION_MARKS_FLOAT'));
        $this->assertIsString(env('QUOTATION_MARKS_FLOAT_SINGLE'));
        $this->assertEquals('8.64', required('QUOTATION_MARKS_FLOAT'));
        $this->assertEquals('8.64', required('QUOTATION_MARKS_FLOAT_SINGLE'));
    }
}

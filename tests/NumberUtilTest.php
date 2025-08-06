<?php

namespace Efaturacim\Util\Tests;

use Efaturacim\Util\Utils\Number\NumberUtil;
use PHPUnit\Framework\TestCase;

class NumberUtilTest extends TestCase
{
    /**
     * @dataProvider coalesceProvider
     */
    public function testCoalesce($expected, ...$args)
    {
        $this->assertEquals($expected, NumberUtil::coalesce(...$args));
    }

    public static function coalesceProvider(): array
    {
        return [
            'first positive value' => [5, null, 0, 5, 10],
            'skips negative value' => [10, null, 0, -1, 10],
            'all null or zero' => [0, null, 0, 0.0],
            'no arguments' => [0],
        ];
    }

    /**
     * @dataProvider isValidRefProvider
     */
    public function testIsValidRef($expected, $ref)
    {
        $this->assertSame($expected, NumberUtil::isValidRef($ref));
    }

    public static function isValidRefProvider(): array
    {
        return [
            'positive integer' => [true, 123],
            'positive integer as string' => [true, '456'],
            'zero' => [false, 0],
            'null' => [false, null],
            'empty string' => [false, ''],
            'negative integer' => [false, -10],
            'non-numeric string' => [false, 'abc'],
        ];
    }

    /**
     * @dataProvider cleanNumberProvider
     */
    public function testCleanNumber($expected, $input, $decimal, $convertToNumber)
    {
        $this->assertEquals($expected, NumberUtil::cleanNumber($input, $decimal, $convertToNumber));
    }

    public static function cleanNumberProvider(): array
    {
        return [
            'comma decimal separator' => [1234.56, '1.234,56', 2, true],
            'dot decimal separator' => [1234.56, '1,234.56', 2, true],
            'with spaces' => [1234.56, '1 234,56', 2, true],
            'as string with padding' => [100, '100.00', 2, false],
            'high precision' => [123.45678, '123,45678', 5, true],
        ];
    }

    /**
     * @dataProvider getAsCleanNumberProvider
     */
    public function testGetAsCleanNumber($expected, $input, $precision)
    {
        $this->assertSame($expected, NumberUtil::getAsCleanNumber($input, $precision));
    }

    public static function getAsCleanNumberProvider(): array
    {
        return [
            'trims trailing zeros' => ['1.23', '1.2300', 4],
            'trims trailing dot' => ['1', '1.000', 3],
            'no change' => ['123.45', 123.45, 2],
        ];
    }

    /**
     * @dataProvider smartReadProvider
     */
    public function testSmartRead($expected, $input, $default, $precision)
    {
        $this->assertEquals($expected, NumberUtil::smartRead($input, $default, $precision));
    }

    public static function smartReadProvider(): array
    {
        return [
            'european format' => [1234.56, '1.234,56', 0, 2],
            'us format' => [1234.56, '1,234.56', 0, 2],
            'invalid string' => [99, 'abc', 99, 2],
            'with precision' => [100.12, '100.12345', 0, 2],
            'null input' => [50, null, 50, 2],
        ];
    }

    /**
     * @dataProvider isIntProvider
     */
    public function testIsInt($expected, $input, $epsilon = null)
    {
        $this->assertSame($expected, NumberUtil::isInt($input, $epsilon));
    }

    public static function isIntProvider(): array
    {
        return [
            'integer' => [true, 123],
            'integer as string' => [true, '123'],
            'float with zero decimal' => [true, 123.0],
            'float with small decimal within epsilon' => [true, 123.00001, 0.001],
            'float with decimal outside epsilon' => [false, 123.1],
            'float as string' => [false, '123.1'],
            'non-numeric string' => [false, 'abc'],
        ];
    }

    /**
     * @dataProvider getPrecisionProvider
     */
    public function testGetPrecision($expected, $number, $maxPrecision)
    {
        $this->assertSame($expected, NumberUtil::getPrecision($number, $maxPrecision));
    }

    public static function getPrecisionProvider(): array
    {
        return [
            'two decimals' => [2, 123.45, 8],
            'zero decimals' => [0, 123, 8],
            'trailing zeros are ignored' => [2, '123.4500', 8],
            'respects max precision' => [8, 123.123456789, 8],
            'string number' => [3, '99.123', 8],
        ];
    }

    /**
     * @dataProvider isPositiveNumberProvider
     */
    public function testIsPositiveNumber($expected, $input)
    {
        $this->assertSame($expected, NumberUtil::isPositiveNumber($input));
    }

    public static function isPositiveNumberProvider(): array
    {
        return [
            'positive int' => [true, 1],
            'positive float' => [true, 0.5],
            'positive string' => [true, '1.23'],
            'zero' => [false, 0],
            'negative int' => [false, -1],
            'non-numeric' => [false, 'abc'],
            'null' => [false, null],
        ];
    }

    /**
     * @dataProvider nearlyEqualProvider
     */
    public function testNearlyEqual($expected, $a, $b, $decimal)
    {
        $this->assertSame($expected, NumberUtil::nearlyEqual($a, $b, $decimal));
    }

    public static function nearlyEqualProvider(): array
    {
        return [
            'equal up to 3 decimals' => [true, 1.0001, 1.0002, 3],
            'not equal up to 4 decimals' => [false, 1.0001, 1.0002, 4],
            'equal with different formats' => [true, '1,001', '1.001', 3],
            'exactly equal' => [true, 5.123, 5.123, 5],
        ];
    }

    /**
     * @dataProvider padProvider
     */
    public function testPad($expected, $number, $length, $padString)
    {
        $this->assertSame($expected, NumberUtil::pad($number, $length, $padString));
    }

    public static function padProvider(): array
    {
        return [
            'pad with zeros' => ['0005', 5, 4, '0'],
            'no padding needed' => ['12345', 12345, 5, '0'],
            'truncate' => ['45', 12345, 2, '0'],
            'pad with another char' => ['--123', 123, 5, '-'],
        ];
    }

    /**
     * @dataProvider folderPathProvider
     */
    public function testFolderPath($expected, $index, $maxSize)
    {
        $this->assertSame($expected, NumberUtil::folderPath($index, $maxSize));
    }

    public static function folderPathProvider(): array
    {
        return [
            'large index' => ['1/1234/1234567', 1234567, 1000],
            'small index' => ['0/0/500', 500, 1000],
            'edge case index' => ['0/0/0', 0, 1000],            
        ];
    }
}
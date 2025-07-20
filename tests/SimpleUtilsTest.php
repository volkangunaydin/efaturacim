<?php

namespace Efaturacim\Util\Tests;

use Efaturacim\Util\StrUtil;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Efaturacim\Util\StrUtil
 */
class SimpleUtilsTest extends TestCase
{
    /**
     * @dataProvider notEmptyDataProvider
     */
    public function testNotEmpty($input, bool $expected)
    {
        // Act
        $result = StrUtil::notEmpty($input);

        // Assert
        $this->assertSame($expected, $result);
    }

    public function notEmptyDataProvider(): array
    {
        return [
            'non-empty string' => ['hello', true],
            'string zero'      => ['0', true],
            'integer'          => [123, true],
            'float'            => [1.23, true],
            'boolean true'     => [true, true],
            'empty string'     => ['', false],
            'null value'       => [null, false],
            'empty array'      => [[], false],
            'non-empty array'  => [['a'], false],
            'object'           => [new \stdClass(), false],
            'boolean false'    => [false, false],
        ];
    }

    /**
     * @dataProvider isJsonDataProvider
     */
    public function testIsJson($input, bool $softCheck, bool $expected)
    {
        // Act
        $result = StrUtil::isJson($input, $softCheck);

        // Assert
        $this->assertSame($expected, $result);
    }

    public function isJsonDataProvider(): array
    {
        return [
            // Strict checks (softCheck = false)
            'valid json object'           => ['{"a":1}', false, true],
            'valid json array'            => ['[1,2,3]', false, true],
            'valid json with whitespace'  => ["  {\"a\":1}  ", false, true],
            'invalid json structure'      => ['{"a":1,}', false, false],
            'plain string'                => ['hello', false, false],
            'empty string'                => ['', false, false],
            'null value'                  => [null, false, false],
            'numeric value'               => [123, false, false], // isJson expects a string

            // Soft checks (softCheck = true)
            'soft check valid object'     => ['{"key":"value"}', true, true],
            'soft check valid array'      => ['[1, 2]', true, true],
            'soft check invalid content'  => ['{invalid-json}', true, true], // Passes because it only checks start/end chars
            'soft check plain string'     => ['hello', true, false],
        ];
    }

    /**
     * @dataProvider removeTurkishCharsDataProvider
     */
    public function testRemoveTurkishChars($input, $expected)
    {
        $this->assertEquals($expected, StrUtil::removeTurkishChars($input));
    }

    public function removeTurkishCharsDataProvider(): array
    {
        return [
            'string with turkish chars' => ['Pijamalı hasta, yağız şoföre çabucak güvendi.', 'Pijamali hasta, yagiz sofore cabucak guvendi.'],
            'uppercase turkish chars'   => ['ĞÜŞİÖÇ', 'GUSIOC'],
            'lowercase turkish chars'   => ['ğüşıöç', 'gusioc'],
            'array of turkish strings'  => [['ĞÜŞİÖÇ', 'ğüşıöç'], ['GUSIOC', 'gusioc']],
            'empty string'              => ['', ''],
            'string with no turkish chars' => ['hello world', 'hello world'],
        ];
    }

    /**
     * @dataProvider toLowerTurkishDataProvider
     */
    public function testToLowerTurkish($input, $expected)
    {
        $this->assertEquals($expected, StrUtil::toLowerTurkish($input));
    }

    public function toLowerTurkishDataProvider(): array
    {
        return [
            'uppercase turkish chars' => ['ĞÜŞIİÖÇ', 'ğüşıiöç'],
            'mixed case string'       => ['PİJAMALI HASTA', 'pijamalı hasta'],
            'array of strings'        => [['Iİ', 'ŞÇ'], ['ıi', 'şç']],
            'empty string'            => ['', ''],
        ];
    }

    /**
     * @dataProvider toUpperTurkishDataProvider
     */
    public function testToUpperTurkish($input, $expected)
    {
        $this->assertEquals($expected, StrUtil::toUpperTurkish($input));
    }

    public function toUpperTurkishDataProvider(): array
    {
        return [
            'lowercase turkish chars' => ['ğüşıiöç', 'ĞÜŞIİÖÇ'],
            'mixed case string'       => ['pijamalı hasta', 'PİJAMALI HASTA'],
            'array of strings'        => [['ıi', 'şç'], ['Iİ', 'ŞÇ']],
            'empty string'            => ['', ''],
        ];
    }

    /**
     * @dataProvider startsWithDataProvider
     */
    public function testStartsWith($haystack, $needle, $expected)
    {
        $this->assertSame($expected, StrUtil::startsWith($haystack, $needle));
    }

    public function startsWithDataProvider(): array
    {
        return [
            'string starts with string' => ['hello world', 'hello', true],
            'string does not start with string' => ['hello world', 'world', false],
            'string starts with one of array' => ['hello world', ['he', 'wor'], true],
            'string does not start with any of array' => ['hello world', ['a', 'b'], false],
            'identical strings' => ['hello', 'hello', true],
            'needle is longer than haystack' => ['hello', 'hello world', false],
            'empty haystack' => ['', 'a', false],
            'empty needle' => ['a', '', true],
            'null haystack' => [null, 'a', false],
        ];
    }

    /**
     * @dataProvider endsWithDataProvider
     */
    public function testEndsWith($haystack, $needle, $expected)
    {
        $this->assertSame($expected, StrUtil::endsWith($haystack, $needle));
    }

    public function endsWithDataProvider(): array
    {
        return [
            'string ends with string' => ['hello world', 'world', true],
            'string does not end with string' => ['hello world', 'hello', false],
            'identical strings' => ['hello', 'hello', true],
            'needle is longer than haystack' => ['hello', 'hello world', false],
            'empty haystack' => ['', 'a', false],
            'empty needle' => ['a', '', false],
            'null haystack' => [null, 'a', false],
        ];
    }

    /**
     * @dataProvider startsWithNumberDataProvider
     */
    public function testStartsWithNumber($input, $expected)
    {
        $this->assertSame($expected, StrUtil::startsWithNumber($input));
    }

    public function startsWithNumberDataProvider(): array
    {
        return [
            'starts with number' => ['123hello', true],
            'does not start with number' => ['hello123', false],
            'starts with space then number' => [' 123hello', false],
            'empty string' => ['', false],
        ];
    }

    /**
     * @dataProvider trimNewLinesDataProvider
     */
    public function testTrimNewLines($input, $replacement, $expected)
    {
        $this->assertEquals($expected, StrUtil::trimNewLines($input, $replacement));
    }

    public function trimNewLinesDataProvider(): array
    {
        return [
            'trims newlines with empty replacement' => [" a\r\nb\n c ", '', 'ab c'],
            'trims newlines with dash replacement' => ["a\r\nb\nc", '-', 'a-b-c'],
            'string with no newlines' => ['abc', '', 'abc'],
        ];
    }

    /**
     * @dataProvider onlyNumericDataProvider
     */
    public function testOnlyNumeric($input, $canBeNegative, $expected)
    {
        $this->assertEquals($expected, StrUtil::onlyNumeric($input, $canBeNegative));
    }

    public function onlyNumericDataProvider(): array
    {
        return [
            'positive numbers only' => ['-1a2b3.4', false, '1234'],
            'can be negative' => ['-1a2b3.4', true, '-1234'],
            'no numbers' => ['abc', false, ''],
        ];
    }



}
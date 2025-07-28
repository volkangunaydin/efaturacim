<?php

namespace Efaturacim\Util\Tests\Utils;

use Efaturacim\Util\Options;
use Efaturacim\Util\Utils\StrNameSurname;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Efaturacim\Util\Utils\StrNameSurname
 */
class StrNameSurnameTest extends TestCase
{
    public function testGetAsResult()
    {
        // Test with standard "Name Surname"
        $result = StrNameSurname::getAsResult("Volkan Günaydın", new Options());
        $this->assertTrue($result->isOK());
        $this->assertEquals("Volkan", $result->attributes['name']);
        $this->assertEquals("Günaydın", $result->attributes['surname']);
        $this->assertEquals(2, $result->attributes['token_count']);

        // Test with "Name MiddleName Surname"
        $result = StrNameSurname::getAsResult("Ali Veli Yılmaz", new Options());
        $this->assertTrue($result->isOK());
        $this->assertEquals("Ali Veli", $result->attributes['name']);
        $this->assertEquals("Yılmaz", $result->attributes['surname']);
        $this->assertEquals(3, $result->attributes['token_count']);
        $this->assertEquals("Ali", $result->attributes['ad1']);
        $this->assertEquals("Veli", $result->attributes['ad2']);

        // Test with "Surname, Name" format
        $result = StrNameSurname::getAsResult("Yılmaz, Ali Veli", new Options());
        $this->assertTrue($result->isOK());
        $this->assertEquals("Yılmaz", $result->attributes['name']);
        $this->assertEquals("Ali Veli", $result->attributes['surname']);

        // Test with single name - should be considered not OK as it expects name and surname
        $result = StrNameSurname::getAsResult("Volkan", new Options());
        $this->assertFalse($result->isOK());
        $this->assertEquals("Volkan", $result->attributes['name']);
        $this->assertEquals("", $result->attributes['surname']);
        $this->assertEquals(1, $result->attributes['token_count']);

        // Test with empty string
        $result = StrNameSurname::getAsResult("", new Options());
        $this->assertFalse($result->isOK());
        $this->assertEquals(0, $result->attributes['token_count']);
    }

    public function testIsEqual()
    {
        $this->assertTrue(StrNameSurname::isEqual("Volkan", "Günaydın", "Volkan", "Günaydın"));
        $this->assertFalse(StrNameSurname::isEqual("Volkan", "Günaydın", "Ali", "Günaydın"));
        $this->assertFalse(StrNameSurname::isEqual("Volkan", "Günaydın", "Volkan", "Yılmaz"));

        // Test case insensitivity (default)
        $this->assertTrue(StrNameSurname::isEqual("volkan", "günaydın", "Volkan", "Günaydın"));

        // Test case sensitivity
        $this->assertFalse(StrNameSurname::isEqual("volkan", "günaydın", "Volkan", "Günaydın", false, false));

        // Test with extra spaces
        $this->assertTrue(StrNameSurname::isEqual(" Volkan ", " Günaydın ", "Volkan", "Günaydın"));
    }

    public function testIsEqualNameSurname()
    {
        $this->assertTrue(StrNameSurname::isEqualNameSurname("Volkan Günaydın", "Volkan Günaydın"));
        $this->assertFalse(StrNameSurname::isEqualNameSurname("Volkan Günaydın", "Ali Yılmaz"));

        // Test case insensitivity (default)
        $this->assertTrue(StrNameSurname::isEqualNameSurname("volkan günaydın", "Volkan Günaydın"));

        // Test case sensitivity
        $this->assertFalse(StrNameSurname::isEqualNameSurname("volkan günaydın", "Volkan Günaydın", false, false));

        // Test with multiple spaces which should be normalized
        $this->assertTrue(StrNameSurname::isEqualNameSurname("Volkan  Günaydın", "Volkan Günaydın"));
    }

    public function testIsEmptyAndIsValid()
    {
        $this->assertFalse(StrNameSurname::isEmpty("Volkan Günaydın"));
        $this->assertTrue(StrNameSurname::isValid("Volkan Günaydın"));

        $this->assertTrue(StrNameSurname::isEmpty("Volkan"));
        $this->assertFalse(StrNameSurname::isValid("Volkan"));

        $this->assertTrue(StrNameSurname::isEmpty(""));
        $this->assertFalse(StrNameSurname::isValid(""));
    }

    public function testGetAsString()
    {
        $this->assertEquals("Volkan Günaydın", StrNameSurname::getAsString("Volkan", "Günaydın"));
        $this->assertEquals("Volkan", StrNameSurname::getAsString("Volkan", null));
        $this->assertNull(StrNameSurname::getAsString(null, "Günaydın"));
        $this->assertEquals("Default Value", StrNameSurname::getAsString(null, "Günaydın", "Default Value"));
    }
}
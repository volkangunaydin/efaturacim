<?php

namespace Efaturacim\Util\Tests\UBLTest;

use Efaturacim\Util\Utils\Xml\XmlToArray;
use PHPUnit\Framework\TestCase;

class ReadXMLTest extends TestCase
{
    /**
     * Örnek XML'i array'e çevirip test eder
     */
    public function testExampleXmlToArray()
    {
        // Buraya örnek XML gelecek
        $exampleXml = '<?xml version="1.0" encoding="UTF-8"?><root><item>test</item></root>';        
        $result = XmlToArray::toArray($exampleXml, true);        
        // Array'in boş olmadığını kontrol et
        $this->assertNotEmpty($result);
        
        // Array olduğunu kontrol et
        $this->assertIsArray($result);
        
        return $result;
    }
}
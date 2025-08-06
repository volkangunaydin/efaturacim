<?php

namespace Efaturacim\Util\Tests;

use Efaturacim\Util\Ubl\Samples\EFaturaSamples;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Efaturacim\Util\Ubl\Turkce\EFaturaBelgesi
 */
class SimpleEFaturaTest extends TestCase
{
    public function testCreateSimpleFatura()
    {
        $extraOptions = array("fatura_no"=>"TST2025000000001","uid"=>"f499cac5-7fee-4f67-9c48-f760c11ca83e");
        $efatura = EFaturaSamples::newFatura("",$extraOptions);
        $xml     = $efatura->toXmlString();

        // Assert
        $this->assertNotEmpty($xml);
        $this->assertEquals("TST2025000000001",$efatura->getBelgeNo(),"Fatura no kontrolu");        
        $this->assertEquals(@$extraOptions["uid"],$efatura->ubl->getGUID(),"GUID no kontrolu");        
    }
}
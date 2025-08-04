<?php

namespace Efaturacim\Util\Tests;

use Efaturacim\Util\Ubl\Turkce\EFaturaBelgesi;
use Efaturacim\Util\Utils\Common\EFaturacimLibUtil;
use Efaturacim\Util\XMLToArray;
use PHPUnit\Framework\TestCase;

class EfaturaXmlTest extends TestCase{
    public function testXmlBRK2025000000052()    {
        $efatura = EFaturaBelgesi::fromXmlFile(EFaturacimLibUtil::getTestPath("xml_data/efatura/BRK2025000000052.xml"));
        $this->assertSame("BRK2025000000052",$efatura->getBelgeNo());
        $this->assertSame("685c9608-0829-4b30-bef8-3684395c844c",$efatura->getBelgeGuid());
        $this->assertEquals(42300,$efatura->ubl->getLineExtensionAmount());
        $this->assertEquals(43443,$efatura->ubl->getPayableAmount());
        
    }
}
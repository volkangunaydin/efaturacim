<?php

namespace Efaturacim\Util\Tests;

use Efaturacim\Util\Ubl\Turkce\EIrsaliyeBelgesi;
use Efaturacim\Util\Utils\Common\EFaturacimLibUtil;
use Efaturacim\Util\XMLToArray;
use PHPUnit\Framework\TestCase;

class EirsaliyeXmlTest extends TestCase{
    public function testXmlKYI2025000001098()    {
        $eirsaliye = EIrsaliyeBelgesi::fromXmlFile(EFaturacimLibUtil::getTestPath("xml_data/eirsaliye/KYI2025000001098.xml"));
        $this->assertSame("KYI2025000001098",$eirsaliye->getBelgeNo());
        $this->assertSame("8276dfc4-deb6-462a-abd5-58dff1704254",$eirsaliye->getBelgeGuid());
        $this->assertSame("2.1",$eirsaliye->ubl->getUBLVersionId());
        $this->assertSame("TEMELIRSALIYE",$eirsaliye->ubl->getProfileId());
        $this->assertSame(1,$eirsaliye->getSatirSayisi());
        $this->assertEquals("2025-07-30 15:55:00",$eirsaliye->getBelgeTarihi());
    }
}
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
        $this->assertSame("2.1",$efatura->ubl->getUBLVersionId());
        $this->assertSame("TICARIFATURA",$efatura->ubl->getProfileId());
        $this->assertSame("TRY",$efatura->ubl->getDocumentCurrencyCode());
        $this->assertSame(6,$efatura->getSatirSayisi());
        $this->assertEquals("BERR YAPI MARKET İNŞ.TAAH.DAN.PAZ.SAN.VEDIŞ TİC.A.Ş. Test Kullanıcısı",$efatura->ubl->accountingSupplierParty->getName());
        $this->assertEquals("5555551470",$efatura->ubl->accountingSupplierParty->getVknOrTckn());
        $this->assertEquals("Ankara Hastanesi İnşaat Sanayi Bilişim Danışmanlık Film teknoloji Yazılım Mühendislik",$efatura->ubl->accountingCustomerParty->getName());
        $this->assertEquals("1111111125",$efatura->ubl->accountingCustomerParty->getVknOrTckn());
        $this->assertEquals("2025-08-04 11:13:00",$efatura->getBelgeTarihi());

        $this->assertEquals(42300,$efatura->ubl->getLineExtensionAmount());
        $this->assertEquals(39650,$efatura->ubl->getLineExtensionAmountFromLines());

        $this->assertEquals(39650,$efatura->ubl->getTaxExclusiveAmount());
        $this->assertEquals(37000,$efatura->ubl->getTaxExclusiveAmountFromLines());

        $this->assertEquals(43583,$efatura->ubl->getTaxInclusiveAmount());
        $this->assertEquals(40933,$efatura->ubl->getTaxInclusiveAmountFromLines());

        $this->assertEquals(2850,$efatura->ubl->getAllowanceTotalAmount());
        $this->assertEquals(2850,$efatura->ubl->getAllowanceTotalAmountFromLines());

        $this->assertEquals(200,$efatura->ubl->getChargeTotalAmount());
        $this->assertEquals(200,$efatura->ubl->getChargeTotalAmountFromLines());

        $this->assertEquals(43443,$efatura->ubl->getPayableAmount());
        $this->assertEquals(40933,$efatura->ubl->getPayableAmountFromLines());
        
        $vatsArray = $efatura->ubl->getVatsAsArray();
        foreach ($vatsArray as $vatKey => $vatData) {
            // percent alanının sayı olduğunu kontrol et
            $this->assertTrue(is_numeric($vatData['percent']));
            
            // taxAmount alanının sayı olduğunu kontrol et
            $this->assertTrue(is_numeric($vatData['taxAmount']));
            
            // taxableAmount alanının sayı olduğunu kontrol et
            $this->assertTrue(is_numeric($vatData['taxableAmount']));
        }
    }
    public function testXmlEAM2025000000087(){
        $efatura = EFaturaBelgesi::fromXmlFile(EFaturacimLibUtil::getTestPath("xml_data/efatura/CNK2025000000026-Iade.xml"));
        $this->assertSame("CNK2025000000026",$efatura->getBelgeNo());
        $this->assertSame("1154c289-a2fb-4868-be5a-d574f253519d",$efatura->getBelgeGuid());
        $this->assertSame("2.1",$efatura->ubl->getUBLVersionId());
        $this->assertSame("TEMELFATURA",$efatura->ubl->getProfileId());
        $this->assertSame("TRY",$efatura->ubl->getDocumentCurrencyCode());
    }
}
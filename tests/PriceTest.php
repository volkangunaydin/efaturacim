<?php

use PHPUnit\Framework\TestCase;
use Efaturacim\Util\Ubl\Objects\Price;
use Efaturacim\Util\Options;

class PriceTest extends TestCase
{
    public function testConstructorWithNullOptions()
    {
        $price = new Price();
        $this->assertNull($price->priceAmount);
        $this->assertEquals('TRY', $price->priceAmountCurrencyID);
        $this->assertNull($price->baseQuantity);
        $this->assertEquals('C62', $price->baseQuantityUnitCode);
    }

    public function testConstructorWithOptions()
    {
        $options = [
            'priceAmount' => 100.50,
            'currency' => 'USD',
            'baseQuantity' => 5.0,
            'baseQuantityUnitCode' => 'KGM'
        ];

        $price = new Price($options);
        $this->assertEquals(100.50, $price->priceAmount);
        $this->assertEquals('USD', $price->priceAmountCurrencyID);
        $this->assertEquals(5.0, $price->baseQuantity);
        $this->assertEquals('KGM', $price->baseQuantityUnitCode);
    }

    public function testSetPropertyFromOptionsPriceAmount()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('priceAmount', '150.75', []);
        $this->assertTrue($result);
        $this->assertEquals(150.75, $price->priceAmount);
    }

    public function testSetPropertyFromOptionsFiyat()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('fiyat', '200.25', []);
        $this->assertTrue($result);
        $this->assertEquals(200.25, $price->priceAmount);
    }

    public function testSetPropertyFromOptionsTutar()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('tutar', '300.00', []);
        $this->assertTrue($result);
        $this->assertEquals(300.00, $price->priceAmount);
    }

    public function testSetPropertyFromOptionsCurrency()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('currency', 'EUR', []);
        $this->assertTrue($result);
        $this->assertEquals('EUR', $price->priceAmountCurrencyID);
    }

    public function testSetPropertyFromOptionsCurrencyID()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('currencyID', 'GBP', []);
        $this->assertTrue($result);
        $this->assertEquals('GBP', $price->priceAmountCurrencyID);
    }

    public function testSetPropertyFromOptionsParaBirimi()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('para_birimi', 'JPY', []);
        $this->assertTrue($result);
        $this->assertEquals('JPY', $price->priceAmountCurrencyID);
    }

    public function testSetPropertyFromOptionsBaseQuantity()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('baseQuantity', '10.5', []);
        $this->assertTrue($result);
        $this->assertEquals(10.5, $price->baseQuantity);
    }

    public function testSetPropertyFromOptionsBirimMiktar()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('birim_miktar', '15.0', []);
        $this->assertTrue($result);
        $this->assertEquals(15.0, $price->baseQuantity);
    }

    public function testSetPropertyFromOptionsBaseQuantityUnitCode()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('baseQuantityUnitCode', 'LTR', []);
        $this->assertTrue($result);
        $this->assertEquals('LTR', $price->baseQuantityUnitCode);
    }

    public function testSetPropertyFromOptionsBirimKodu()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('birim_kodu', 'MTK', []);
        $this->assertTrue($result);
        $this->assertEquals('MTK', $price->baseQuantityUnitCode);
    }

    public function testSetPropertyFromOptionsInvalidProperty()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('invalidProperty', 'value', []);
        $this->assertFalse($result);
    }

    public function testSetPropertyFromOptionsNonNumericPriceAmount()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('priceAmount', 'not_a_number', []);
        $this->assertFalse($result);
        $this->assertNull($price->priceAmount);
    }

    public function testSetPropertyFromOptionsEmptyCurrency()
    {
        $price = new Price();
        $result = $price->setPropertyFromOptions('currency', '', []);
        $this->assertFalse($result);
        $this->assertEquals('TRY', $price->priceAmountCurrencyID);
    }

    public function testIsEmptyWhenPriceAmountIsNull()
    {
        $price = new Price();
        $this->assertTrue($price->isEmpty());
    }

    public function testIsEmptyWhenPriceAmountIsSet()
    {
        $price = new Price(['priceAmount' => 100.0]);
        $this->assertFalse($price->isEmpty());
    }

    public function testToDOMElementWhenEmpty()
    {
        $price = new Price();
        $document = new DOMDocument();
        $element = $price->toDOMElement($document);
        $this->assertNull($element);
    }

    public function testToDOMElementWithPriceAmountOnly()
    {
        $price = new Price(['priceAmount' => 150.75, 'currency' => 'USD']);
        $document = new DOMDocument();
        $element = $price->toDOMElement($document);
        
        $this->assertNotNull($element);
        $this->assertEquals('cac:Price', $element->tagName);
        
        $priceAmountElement = $element->getElementsByTagName('cbc:PriceAmount')->item(0);
        $this->assertNotNull($priceAmountElement);
        $this->assertEquals('150.75', $priceAmountElement->textContent);
        $this->assertEquals('USD', $priceAmountElement->getAttribute('currencyID'));
    }

    public function testToDOMElementWithAllProperties()
    {
        $price = new Price([
            'priceAmount' => 200.50,
            'currency' => 'EUR',
            'baseQuantity' => 5.0,
            'baseQuantityUnitCode' => 'KGM'
        ]);
        
        $document = new DOMDocument();
        $element = $price->toDOMElement($document);
        
        $this->assertNotNull($element);
        $this->assertEquals('cac:Price', $element->tagName);
        
        $priceAmountElement = $element->getElementsByTagName('cbc:PriceAmount')->item(0);
        $this->assertNotNull($priceAmountElement);
        $this->assertEquals('200.50', $priceAmountElement->textContent);
        $this->assertEquals('EUR', $priceAmountElement->getAttribute('currencyID'));
        
        $baseQuantityElement = $element->getElementsByTagName('cbc:BaseQuantity')->item(0);
        $this->assertNotNull($baseQuantityElement);
        $this->assertEquals('5.00', $baseQuantityElement->textContent);
        $this->assertEquals('KGM', $baseQuantityElement->getAttribute('unitCode'));
    }

    public function testToDOMElementWithBaseQuantityNull()
    {
        $price = new Price(['priceAmount' => 100.0]);
        $document = new DOMDocument();
        $element = $price->toDOMElement($document);
        
        $this->assertNotNull($element);
        $baseQuantityElement = $element->getElementsByTagName('cbc:BaseQuantity')->item(0);
        $this->assertNull($baseQuantityElement);
    }

    public function testNumberFormatting()
    {
        $price = new Price(['priceAmount' => 123.456789, 'baseQuantity' => 7.123456]);
        $document = new DOMDocument();
        $element = $price->toDOMElement($document);
        
        $priceAmountElement = $element->getElementsByTagName('cbc:PriceAmount')->item(0);
        $this->assertEquals('123.46', $priceAmountElement->textContent);
        
        $baseQuantityElement = $element->getElementsByTagName('cbc:BaseQuantity')->item(0);
        $this->assertEquals('7.12', $baseQuantityElement->textContent);
    }
} 
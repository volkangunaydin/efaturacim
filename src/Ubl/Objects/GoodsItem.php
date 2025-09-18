<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class GoodsItem extends UblDataType
{
    public ?string $requiredCustomsID = null;
        /**
     * Summary of valueAmount
     * @var ValueAmount
     */
    public ?ValueAmount $valueAmount = null;   
    public ?InvoiceLine $invoiceLine = null;
    
    public function initMe(){
        $this->valueAmount = new ValueAmount();
        $this->invoiceLine = new InvoiceLine();
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {        
        if (in_array($k, ['valueAmount', 'deger_tutari']) && NumberUtil::isNumberString($v)) {
            $this->valueAmount->setValue((float)$v);
            return true;
        }                   
        if (in_array($k, ['currency', 'currencyID', 'para_birimi']) && StrUtil::notEmpty($v)) {
            $this->valueAmount->setCurrencyID($v);
            return true;
        } 
        if (in_array($k, ['invoiceLine', 'fatura_satir']) && StrUtil::notEmpty($v)) {
            $this->invoiceLine->id = $v;
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->requiredCustomsID) && is_null($this->valueAmount);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {            
            return null;
        }
        $element = $document->createElement('cac:GoodsItem');
        $this->appendElement($document, $element, 'cbc:RequiredCustomsID', $this->requiredCustomsID);
        
        if ($this->valueAmount && !$this->valueAmount->isEmpty()) {
            $this->appendChild($element, $this->valueAmount->toDOMElement($document));
        }
        
        if ($this->invoiceLine && !$this->invoiceLine->isEmpty()) {
            $this->appendChild($element, $this->invoiceLine->toDOMElement($document));
        }
        
        return $element;
    }
}
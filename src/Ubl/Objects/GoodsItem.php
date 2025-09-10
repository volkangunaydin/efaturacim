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
    
    public function initMe(){
        $this->valueAmount = new ValueAmount();
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
        $this->appendChild($element,$this->valueAmount->toDOMElement($document));
        return $element;
    }
}
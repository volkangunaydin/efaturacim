<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\String\StrUtil;

class Price extends UblDataType
{
    public ?PriceAmount $priceAmount = null;


    public ?BaseQuantity $baseQuantity = null;
    public ?AllowanceCharge $allowanceCharge = null;
    

    public function initMe(){
        $this->setDefaultTagNameIfNotSet("cac:Price");
        $this->priceAmount  = new PriceAmount();
        $this->baseQuantity = new BaseQuantity();
        $this->allowanceCharge = new AllowanceCharge();
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->priceAmount) || $this->priceAmount->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $this->createElement($document,$this->defaultTagName);

        $this->appendChild($element,$this->priceAmount->toDOMElement($document));
        return $element;
    }
}
<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class TransportHandlingUnit extends UblDataType
{
    public UblDataTypeList $actualPackage;
    
    public function initMe(){

    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {                   
        $this->actualPackage = new UblDataTypeList(ActualPackage::class);         
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->actualPackage);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {            
            return null;
        }
        $element = $document->createElement('cac:TransportHandlingUnit');
        $this->appendChild($element,$this->actualPackage->toDOMElement($document));

        return $element;
    }
}
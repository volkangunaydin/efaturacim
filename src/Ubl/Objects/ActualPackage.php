<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class ActualPackage extends UblDataType
{
    public ?string $id = null;
    public ?string $quantity = null;
    public ?string $packagingTypeCode = null;
    
    public function initMe(){

    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {                            
        return false;
    }

    public function isEmpty(): bool
    {
        return empty($this->id) && empty($this->quantity) && empty($this->packagingTypeCode);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {            
            return null;
        }
        $element = $document->createElement('cac:ActualPackage');
        $this->appendElement($document, $element, 'cbc:ID', $this->id);
        $this->appendElement($document, $element, 'cbc:Quantity', $this->quantity);
        $this->appendElement($document, $element, 'cbc:PackagingTypeCode', $this->packagingTypeCode);

        return $element;
    }
}
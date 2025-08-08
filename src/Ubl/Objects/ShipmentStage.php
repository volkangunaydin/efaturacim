<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class ShipmentStage extends UblDataType
{
    public ?string $transportModeCode = null;
    
    public function initMe(){

    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {                            
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->transportModeCode);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {            
            return null;
        }
        $element = $document->createElement('cac:ShipmentStage');
        $this->appendElement($document, $element, 'cbc:TransportModeCode', $this->transportModeCode);

        return $element;
    }
}
<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class GoodsItem extends UblDataType
{
    public ?string $requiredCustomsID = null;
    
    public function initMe(){

    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {                            
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->requiredCustomsID);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {            
            return null;
        }
        $element = $document->createElement('cac:GoodsItem');
        $this->appendElement($document, $element, 'cbc:RequiredCustomsID', $this->requiredCustomsID);

        return $element;
    }
}
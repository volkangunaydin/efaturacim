<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class ActualPackage extends UblDataType
{
    public ?ID $id = null;
    public ?Quantity $quantity = null;
    public ?PackagingTypeCode $packagingTypeCode = null;

    public function initMe() {
        $this->id = new ID();
        $this->quantity = new Quantity();
        $this->packagingTypeCode = new PackagingTypeCode();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement {
        $element = $document->createElement('cac:ActualPackage');
        if ($this->id && !$this->id->isEmpty()) {
            $element->appendChild($this->id->toDOMElement($document));
        }
        if ($this->quantity && !$this->quantity->isEmpty()) {
            $element->appendChild($this->quantity->toDOMElement($document));
        }
        if ($this->packagingTypeCode && !$this->packagingTypeCode->isEmpty()) {
            $element->appendChild($this->packagingTypeCode->toDOMElement($document));
        }
        return $element;
    }

    public function isEmpty(): bool {
        return is_null($this->id) || $this->id->isEmpty();
    }

    public function setPropertyFromOptions($k, $v, $options) {
        return false;
    }
}
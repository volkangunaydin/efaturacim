<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;

class BillingReference extends UblDataType
{
    public ?InvoiceDocumentReference $invoiceDocumentReference = null;
    
    public function initMe(){
        $this->invoiceDocumentReference              = new InvoiceDocumentReference();
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {                            
        return false;
    }

    public function isEmpty(): bool
    {
        return is_null($this->invoiceDocumentReference) || $this->invoiceDocumentReference->isEmpty();
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {            
            return null;
        }
        $element = $document->createElement('cac:BillingReference');
        $this->appendChild($element, $this->invoiceDocumentReference->toDOMElement($document));

        return $element;
    }
}